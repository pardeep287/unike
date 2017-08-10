<?php

namespace App\Http\Controllers;

/**
 * :: Invoice Controller ::
 * To manage invoices.
 *
 **/


use App\Order;
use App\OrderProducts;
use App\OrderProductSizes;
use App\Product;
use App\Customer;
use App\Company;
use App\Invoice;
use App\InvoiceItems;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('order.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param null $id
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        if(!getActiveFinancialYear()) {
            return redirect()->route('invoice.index')
                ->with('error', lang('common.add_financial_year'));
        }

        if(!getActiveBank()) {
            return redirect()->route('invoice.index')
                ->with('error', lang('common.add_banks'));
        }

        $bank = (new Bank)->getBankService();
        $invoiceNumber = (new Invoice)->getNewPONumber();
        $products = (new Product)->getProductsService();
        $saleType = (new SaleType)->getSaleTypesService();
        return view('invoice.create', compact('bank', 'products', 'saleType', 'invoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */

    public function store()
    {
        $inputs = \Input::all();
        $validator = (new Invoice)->validateInvoice($inputs);

        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());
        }

        try
        {
            \DB::beginTransaction();
            $invoiceDate = $inputs['invoice_date'] . ' ' . date('H:i:s');
            $invoiceDate = dateFormat('Y-m-d H:i:s', $invoiceDate);
            unset($inputs['invoice_date']);

            $orderDate = null;
            if($inputs['order_date'] != "") {
                $orderDate = $inputs['order_date'];
                $orderDate = dateFormat('Y-m-d', $orderDate);
            }
            unset($inputs['order_date']);

            $account = $inputs['account'];
            unset($inputs['account']);
            //$ledger = $inputs['ledger'];
            unset($inputs['sale_type']);

            $product = (new Product)->getProductEffectedTax($inputs['product'], convertToUtc($invoiceDate));
            if (!$product) {
                $errors = collect(['' => [lang('messages.product_tax_not_defined')]]);
                return validationResponse(false, 206, "", "", $errors);
            }

            $cgst = ($product->cgst_rate != "") ? $product->cgst_rate : 0;
            $sgst = ($product->sgst_rate != "") ? $product->sgst_rate : 0;
            $igst = ($product->igst_rate != "") ? $product->igst_rate : 0;

            $price = ($inputs['manual_price'] != "") ? $inputs['manual_price'] : $inputs['price'];
            $quantity = $inputs['quantity'];

            $total = round($price * $quantity, 2);

            $cgstRate = round(($total * $cgst)/100, 2);
            $sgstRate = round(($total * $sgst)/100, 2);
            $igstRate = round(($total * $igst)/100, 2);

            $inputs = $inputs + [
                'invoice_date'      => convertToUtc($invoiceDate),
                'order_date'        => ($orderDate != "") ? $orderDate : null,
                'account_id'        => $account,
                //'ledger_id'         => $ledger,

                'cgst'              => $cgst,
                'sgst'              => $sgst,
                'igst'              => $igst,
                'total_price'       => $total,

                'cgst_amount'       => $cgstRate,
                'sgst_amount'       => $sgstRate,
                'igst_amount'       => $igstRate,

                'created_by'        => authUserId(),
                'company_id'        => loggedInCompanyId(),
                'financial_year_id' => financialYearId(),
            ];

            $id = (new Invoice)->store($inputs);

            $totalTax = 0;
            if ($inputs['sale'] == 1) {
                $totalTax = getRoundedAmount($cgstRate) + getRoundedAmount($sgstRate);
            } elseif($inputs['sale'] == 2) {
                $totalTax = getRoundedAmount($igstRate);
            }

            $grossTotal = $total;
            $netTotal = ($grossTotal + $totalTax + $inputs['freight'] + $inputs['other_charges']) + $inputs['round_off'];
            $update = [
                'cgst_total'       => $cgstRate,
                'sgst_total'       => $sgstRate,
                'igst_total'       => $igstRate,

                'gross_amount'  => $grossTotal,
                'net_amount'    => $netTotal,
            ];
            (new Invoice)->invoiceUpdate($update, $id);
            $this->storeToTransaction($inputs, $account, $id, $netTotal);
            $invoiceNumber = '';
            $invoiceNumber = (new Invoice)->getInvoiceNumber($id);
            $inputs['ref_id'] = $id;
            $this->storeToVoucher($inputs, $account, $netTotal, $invoiceNumber['invoice_number']);
            \DB::commit();
            $route = route('invoice.index');
            $lang = lang('messages.created', lang('invoice.invoice'));
            if (isset($inputs['addmore'])) {
                $route = route('invoice.edit', ['id' => $id, 't' => 'edit', 'a' => '1']);
                $lang = lang('messages.created', lang('invoice.invoice'));
            }
            return validationResponse(true, 201, $lang, $route);
        }   catch (\Exception $exception) {
            \DB::rollBack();
            return validationResponse(false, 207, lang('messages.server_error'));
        }
    }
    /**
     * @param $inputs
     * @param $accountId
     * @param $amount
     * @param $invoiceNumber
     * @param $id
     */
    public function storeToVoucher($inputs, $account, $amount, $invoiceNumber, $id = null)
    {
        if($inputs['cash_credit'] == 1) {
            if($id > 0) {
                (new Voucher)->dropVoucher(1, $id);
            }
            $items = [];
            $voucherDate = $inputs['invoice_date'];
            $inputs['account_id'] = (int) $account;
            $inputs['net_amount'] = $amount;
            //dd($inputs);
            $inputs = $inputs + [
                    'type'          => 1,
                    'voucher_date'  => $voucherDate,
                    'company_id'    => loggedInCompanyId(),
                    'created_by'    => authUserId(),
                    'financial_year_id' => financialYearId()
                ];
            $items = array_only($inputs, [ 'type', 'financial_year_id', 'company_id',
                'voucher_date', 'created_by', 'net_amount', 'account_id', 'ref_id'
            ]);
            $items = $items + [
                    'd_c'       => [ 1, 2 ],
                    'account'   => [ $items['account_id'], getTransactionTypes('c') ],
                    'amount'    => [ $items['net_amount'] , $items['net_amount'] ],
                    'narration' => [ '', lang('transaction.sale_against') . paddingLeft($invoiceNumber) ]
                ];
            (new Voucher)->store($items);
        }
        elseif($inputs['cash_credit'] == 2) {
            if($id > 0) {
                (new Voucher)->dropVoucher(1, $id);
            }
        }

    }

    /**
     * @param $inputs
     * @param $accountId
     * @param $typeId
     * @param $amount
     * @param $id
     */

    /**
     * @param $inputs
     * @param $accountId
     * @param $typeId
     * @param $amount
     * @param $id
     */
    public function storeToTransaction($inputs, $accountId, $typeId, $amount, $id = null)
    {

        $inputs['type_id'] = $typeId;
        $inputs['account_id'] = $accountId;
        $inputs['amount'] = $amount;
        $inputs = array_only($inputs, [
            'type_id',
            'account_id',
            'amount',
            'cash_credit',
            'invoice_date',
            'invoice_number'
        ]);

        if($inputs['cash_credit'] == 1) {
            if($id > 0) {
                (new TransactionMaster)->dropTransaction('S', $id);
            }
            // cash entry
            $inputs['is_cash'] = 1;
        } elseif($inputs['cash_credit'] == 2) {
            if($id > 0) {
                (new TransactionMaster)->dropTransaction('S', $id, true);
            }
            // sale entry
        }
        (new TransactionMaster)->storeInvoiceTransaction($inputs);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @param null $itemId
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $itemId = null)
    {
        $result = (new Order)->getOrderDetail($id);
        $t = \Input::get('t');
        $a = \Input::get('a');
        if (!$result) {
            abort(404);
        }

        //$bank = (new Bank)->getBankService();
        $products = (new OrderProducts)->getProductsByOrderId($id);
        //$products = (new OrderProducts)->getProductsService();
        $items = (new OrderProductSizes)->getInvoiceItems(['order_id' => $id]);
        $itemCountProductWise=[];
        foreach($products as $product){
            $count=0;
            foreach ($items as $item){
                if($item['product_id']==$product['product_id']){
                    $count++;
                }
            }
            $itemCountProductWise[$product['product_id']]=$count;
        }



       // dd($items->toArray(),$result->toArray(),$products->toArray());
        return view('order.edit', compact('result', 'items', 't', 'a' , 'products','itemCountProductWise'));
    }

    /**
     * @param $id
     * @return array|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        return $this->addMoreUpdateCommon($id);
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addMoreUpdateCommon($id)
    {
        $result = (new Invoice)->company()->find($id);
        if (!$result) {
            return redirect()->route('invoice.index')
                ->with('error', lang('messages.invalid_id', string_manip(lang('sale_invoice.sale_invoice'))));
        }
        $inputs = \Input::all();
        $a = ($inputs['a'] == 1) ? 1 : 0;
        $validator = (new Invoice)->validateInvoice($inputs, $id);
        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());
        }

        if (isset($inputs['update_item']) && $inputs['update_item'] != null) {
            return $this->updateItem($id, $inputs);
        }

        try {
            \DB::beginTransaction();
            $invoiceDate = $inputs['invoice_date'];
            $invoiceTime = dateFormat('H:i:s', $inputs['invoice_time']);
            $invoiceDate = dateFormat('Y-m-d H:i:s', $invoiceDate . ' ' . $invoiceTime);
            unset($inputs['invoice_date']);

            $orderDate = null;
            if($inputs['order_date'] != "") {
                $orderDate = $inputs['order_date'] . ' ' . date('H:i:s');
                $orderDate = dateFormat('Y-m-d', $orderDate);
            }
            unset($inputs['order_date']);

            $account = $inputs['account'];
            unset($inputs['account']);
            $ledger = $inputs['ledger'];
            unset($inputs['ledger']);


            $cgst = $sgst = $igst = $cgstRate = $sgstRate = $igstRate = $total = 0;

            if ($inputs['product'] != "" && $inputs['quantity'] != "") {
                $product = (new Product)->getProductEffectedTax($inputs['product'], convertToUtc($invoiceDate));

                if (!$product) {
                    $errors = collect(['' => [lang('messages.product_tax_not_defined')]]);
                    return validationResponse(false, 206, "", "", $errors);
                }

                $cgst = ($product->cgst_rate != "") ? $product->cgst_rate : 0;
                $sgst = ($product->sgst_rate != "") ? $product->sgst_rate : 0;
                $igst = ($product->igst_rate != "") ? $product->igst_rate : 0;

                $price = ($inputs['manual_price'] != "") ? $inputs['manual_price'] : $inputs['price'];
                $quantity = $inputs['quantity'];

                $total = round($price * $quantity, 2);

                $cgstRate = round(($total * $cgst) / 100, 2);
                $sgstRate = round(($total * $sgst) / 100, 2);
                $igstRate = round(($total * $igst) / 100, 2);
            }

            $inputs = $inputs + [
                'invoice_date'      => convertToUtc($invoiceDate),
                'order_date'        => ($orderDate != "") ? $orderDate : null,
                'account_id'        => $account,
                'ledger_id'         => $ledger,

                'cgst'              => $cgst,
                'sgst'              => $sgst,
                'igst'              => $igst,
                'total_price'       => $total,

                'cgst_amount'       => $cgstRate,
                'sgst_amount'       => $sgstRate,
                'igst_amount'       => $igstRate,

                'created_by'        => authUserId(),
                'company_id'        => loggedInCompanyId(),
                'financial_year_id' => financialYearId(),
            ];
            //dd($inputs);
            (new Invoice)->store($inputs, $id, true);

            $totalTax = 0;
            //if ($inputs['product'] != "" && $inputs['quantity'] != "") {
                if ($inputs['sale'] == 1) {
                    $totalTax = ($result->cgst_total + getRoundedAmount($cgstRate) + ($result->sgst_total + getRoundedAmount($sgstRate)));
                } elseif ($inputs['sale'] == 2) {
                    $totalTax = ($result->igst_total + getRoundedAmount($igstRate));
                }
            //}

            $grossTotal = $inputs['gross_total'] + $total;
            $netTotal = ($grossTotal + $totalTax + $inputs['freight'] + $inputs['other_charges']) + $inputs['round_off'];
            $update = [
                'cgst_total'    => $result->cgst_total + $cgstRate,
                'sgst_total'    => $result->sgst_total + $sgstRate,
                'igst_total'    => $result->igst_total + $igstRate,
                'gross_amount'  => $grossTotal,
                'net_amount'    => $netTotal,
            ];
            //dd($update);
            (new Invoice)->invoiceUpdate($update, $id);
            $this->storeToTransaction($inputs, $account, $id, $netTotal, $id);
            $invoiceNumber = '';
            $invoiceNumber = (new Invoice)->getInvoiceNumber($id);
            $inputs['ref_id'] = $id;
            $this->storeToVoucher($inputs, $account, $netTotal, $invoiceNumber['invoice_number'], $id);
            /*(new StockMaster)->deleteStock($id, 2);
            $items = (new InvoiceItems)->getInvoicesItems(['invoice_id' => $id]);
            (new StockMaster)->saveStock($items, $id, 2);*/
            \DB::commit();
            $route = route('invoice.index');
            $lang = lang('messages.updated', lang('invoice.invoice'));
            if (isset($inputs['addmore'])) {
                $param = ($a == 1) ? ['id' => $id, 't' => 'edit', 'a' => $a] : ['id' => $id, 't' => 'edit'];
                $route = route('invoice.edit', $param);
                $lang = lang('messages.itemadded', lang('invoice.invoice'));
            }
            return validationResponse(true, 201, $lang, $route);
        } catch (\Exception $exception) {
            \DB::rollBack();
            return validationResponse(false, 207, lang('messages.server_error'));
        }
    }

    /**
     * @param $id
     * @param null $itemId
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function editItem($id, $itemId = null)
    {
      $result = (new Invoice)->company()->find($id);
      if (!$result) {
        abort(401);
      }
      // dd($result);
      $grossTotal = $result['gross_amount'];

      $items = (new InvoiceItems)->getInvoiceItem(['id' => $itemId]);
      if (!$items) {
            return redirect()->route('invoice.edit', $id)
            ->with('error', lang('messages.invalid_id', string_manip(lang('invoice.invoice'))));
      }

      $totalPrice = $items['total_price'];
      $inputs = [
          'itemId'        => $itemId,
          'product'      => $items['product_id'],
          'hsn_code'      => $items['hsn_code'],
          'unit'          => $items['unit'],
          'tax_group'     => $items['tax_group'],
          'quantity'     => $items['quantity'],
          'price'         => $items['price'],
          'manual_price'  => $items["manual_price"],
          'tax_id'       => $items["tax_id"],
          'prevQty'       => $items['quantity'],
          'gross_total'   => $grossTotal,
      ];

      return redirect()->route('invoice.edit', ['id' => $id, 't' => 'edit'])
        ->with(array('totalPrice' => $totalPrice, 'productId' => $items['product_id'], 'update' => 1))
        ->withInput($inputs);
    }

    /**
     * @param $id
     * @param $inputs
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function updateItem($id, $inputs)
    {
        try {
            \DB::beginTransaction();
            // default values
            $itemId = $inputs['itemId'];

            // invoice updation
            $invoiceDate = $inputs['invoice_date'];
            $invoiceTime = dateFormat('H:i:s', $inputs['invoice_time']);
            $invoiceDate = dateFormat('Y-m-d H:i:s', $invoiceDate . ' ' . $invoiceTime);
            unset($inputs['invoice_date']);

            $orderDate = null;
            if($inputs['order_date'] != "") {
                $orderDate = $inputs['order_date'] . ' ' . date('H:i:s');
                $orderDate = dateFormat('Y-m-d', $orderDate);
            }
            unset($inputs['order_date']);

            $productId = $inputs['product'];
            unset($inputs['product']);
            $account = $inputs['account'];
            unset($inputs['account']);


            $newTotalPrice = 0;
            $price = ($inputs['manual_price'] == '') ? $inputs['price'] : $inputs['manual_price'];
            if ($productId != '' && $inputs['quantity'] != '') {
                $newTotalPrice = $price * $inputs['quantity'];
            }

            //Old product assigned effected tax rates
            $oldInvoice = (new Invoice)->company()->find($id);

            $oldInvoiceItem = (new InvoiceItems)->where('id', $itemId)
                ->where('invoice_id', $id)
                ->first(
                    [
                        'cgst_amount',
                        'sgst_amount',
                        'igst_amount',
                        'total_price'
                    ]
                );

            $oldTotalTax = 0;
            if ($oldInvoice->sale == 1) {
                $oldTotalTax = $oldInvoiceItem->cgst_amount + $oldInvoiceItem->sgst_amount;
            } elseif($oldInvoice->sale == 2) {
                $oldTotalTax = $oldInvoiceItem->igst_amount;
            }

            $oldTotal = $oldInvoiceItem->total_price;
            $oldGrossAmount = $oldInvoice->gross_amount;
            $deductedOldGrossAmount = ($oldGrossAmount - $oldTotal);

            $oldCgstAmount = $oldInvoice->cgst_total - $oldInvoiceItem->cgst_amount;
            $oldSgstAmount = $oldInvoice->sgst_total - $oldInvoiceItem->sgst_amount;
            $oldIgstAmount = $oldInvoice->igst_total - $oldInvoiceItem->igst_amount;

            //New product effected tax rates
            $product = (new Product)->getProductEffectedTax($productId, convertToUtc($invoiceDate));
            if (!$product) {
                $errors = collect(['' => [lang('messages.product_tax_not_defined')]]);
                return validationResponse(false, 206, "", "", $errors);
            }

            $cgst = ($product->cgst_rate != "") ? $product->cgst_rate : 0;
            $sgst = ($product->sgst_rate != "") ? $product->sgst_rate : 0;
            $igst = ($product->igst_rate != "") ? $product->igst_rate : 0;

            $newTotalPrice = 0;
            $price = ($inputs['manual_price'] != "") ? $inputs['manual_price'] : $inputs['price'];
            $quantity = $inputs['quantity'];
            $newTotalPrice = round($price * $quantity, 2);

            $cgstRate = round(($newTotalPrice * $cgst) / 100, 2);
            $sgstRate = round(($newTotalPrice * $sgst) / 100, 2);
            $igstRate = round(($newTotalPrice * $igst) / 100, 2);

            $itemArray = [
                'product'           => $productId,

                'cgst'              => $cgst,
                'sgst'              => $sgst,
                'igst'              => $igst,

                'cgst_amount'       => $cgstRate,
                'sgst_amount'       => $sgstRate,
                'igst_amount'       => $igstRate,

                'price'             => $inputs['price'],
                'manual_price'      => $inputs['manual_price'],
                'quantity'          => $inputs['quantity'],
                'total_price'       => $newTotalPrice
            ];

            (new InvoiceItems)->store($itemArray, $itemId);

            $newTotalTax = 0;
            if ($inputs['sale'] == 1) {
                $newTotalTax = ($oldCgstAmount + getRoundedAmount($cgstRate)) + ($oldSgstAmount + getRoundedAmount($sgstRate));
            } elseif($inputs['sale'] == 2) {
                $newTotalTax = ($oldIgstAmount + $igstRate);
            }
            $newGrossAmount = $deductedOldGrossAmount + $newTotalPrice;
            $grossTotal = $newGrossAmount;
            $netTotal = ($grossTotal + $newTotalTax + $inputs['freight'] + $inputs['other_charges']) + $inputs['round_off'];

            $invoiceArray = $inputs + [
                'invoice_date'  => convertToUtc($invoiceDate),
                'order_date'    => ($orderDate != "") ? $orderDate : null,
                'account_id'    => $account,
                'cgst_total'    => $oldCgstAmount + $cgstRate,
                'sgst_total'    => $oldSgstAmount + $sgstRate,
                'igst_total'    => $oldIgstAmount + $igstRate,
                'gross_amount' => $grossTotal,
                'net_amount' => $netTotal,
            ];
            (new Invoice)->invoiceUpdate($invoiceArray, $id);
            $inputs['invoice_date'] = convertToUtc($invoiceDate);
            $this->storeToTransaction($inputs, $account, $id, $netTotal, $id);
            $invoiceNumber = '';
            $invoiceNumber = (new Invoice)->getInvoiceNumber($id);
            $inputs['ref_id'] = $id;
            $this->storeToVoucher($inputs, $account, $netTotal, $invoiceNumber['invoice_number'], $id);
            // stock master code
            /*(new StockMaster)->deleteStock($id, 2);
            $items = (new InvoiceItems)->getInvoiceItems(['invoice_id' => $id]);
            (new StockMaster)->saveStock($items, $id, 2);*/

            \DB::commit();
            $route = route('invoice.edit', ['id' => $id, 't' => 'edit']);
            $lang = lang('messages.updated', lang('invoice.invoice'));
            return validationResponse(true, 201, $lang, $route);
        }
        catch (\Exception $exception) {
            \DB::rollBack();
            return array('type' => 'error', 'message' => lang('messages.server_error'));
        }
    }

    /**
     * Used to load more records and render to view.
     *
     * @param int $pageNumber
     * @return \Illuminate\Http\Response
     */
    public function orderPaginate($pageNumber = null)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }

        $inputs = \Input::all();
        $page = 1;
        if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
            $page = $inputs['page'];
        }

        $perPage = 20;
        if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
            $perPage = $inputs['perpage'];
        }

        $start = ($page - 1) * $perPage;
        if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
            // $inputs = array_filter($inputs);
            unset($inputs['_token']);
            $data = (new Order)->getOrders($inputs, $start, $perPage);
            $total = (new Order)->totalOrders($inputs);
            $total = $total->total;
        } else {
            $data = (new Order)->getOrders($inputs, $start, $perPage);
            $total = (new Order)->totalOrders($inputs);
            $total = $total->total;
        }
        //dd($data->toArray(),$total);
        return view('order.load_data', compact('inputs', 'data', 'total', 'page', 'perPage'));
    }

    /**
     * @param $id
     * @param $itemId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dropItem($id, $itemId)
    {
        $result = (new Invoice)->company()->find($id);
        if (!$result) {
            return json_encode(array(
                'status' => 0,
                'message' => lang('messages.invalid_id', string_manip(lang('invoice.invoice')))
            ));
        }
        $resultItem = (new InvoiceItems)->getInvoiceItems(['invoice_id' => $id]);
        if (count($resultItem) > 0)
        {
            try {
                \DB::beginTransaction();
                $items = array_column($resultItem->toArray(), 'total_price', 'id');
                $cgstAmount = array_column($resultItem->toArray(), 'cgst_amount', 'id');
                $sgstAmount = array_column($resultItem->toArray(), 'sgst_amount', 'id');
                $igstAmount = array_column($resultItem->toArray(), 'igst_amount', 'id');
                if (array_key_exists($itemId, $items)) {
                    (new InvoiceItems)->dropItem($itemId);
                    $cgstRate = $cgstAmount[$itemId];
                    $sgstRate = $sgstAmount[$itemId];
                    $igstRate = $igstAmount[$itemId];
                    if($result->sale == 1) {
                        $oldTax = ($cgstRate + $sgstRate);
                        $totalTax = ($result->cgst_total - $cgstRate) + ($result->sgst_total - $sgstRate);
                    } elseif($result->sale == 2) {
                        $oldTax = $igstRate;
                        $totalTax = ($result->igst_total - $igstRate);
                    }

                    $total = ($result->gross_amount - $items[$itemId]);
                    $netTotal = ($result->net_amount - $items[$itemId] - $oldTax);
                    $invoiceArray = [
                        'cgst_total'    => $result->cgst_total - $cgstRate,
                        'sgst_total'    => $result->sgst_total - $sgstRate,
                        'igst_total'    => $result->igst_total - $igstRate,
                        'gross_amount' => $total,
                        'net_amount' => $netTotal,
                    ];
                    (new Invoice)->find($id)->update($invoiceArray);
                    $inputs['account_id'] = $result->account_id;
                    $inputs['invoice_date'] = $result->invoice_date;
                    $inputs['invoice_number'] = $result->invoice_number;
                    $inputs['cash_credit'] = $result->cash_credit;
                    $this->storeToTransaction($inputs, $result->account_id, $id, $netTotal, $id);
                    $inputs['ref_id'] = $id;
                    $this->storeToVoucher($inputs, $result->account_id, $netTotal, $result->invoice_number, $id);
                }
                \DB::commit();
                return json_encode(array(
                    'status' => 1,
                    'message' => lang('messages.itemDeleted', lang('invoice.invoice'))
                ));
            }
            catch (\Exception $exception) {
                \DB::rollBack();
                return json_encode(array(
                    'status' => 0,
                    'message' => lang('messages.server_error'))
                );
            }
        }
        return json_encode(array(
           'status' => 0,
           'message' => lang('messages.invalid_id', string_manip(lang('invoice.invoice')))
         ));
    }

    /**
     * @return String
     */
    public function getInvoicesSearch($id)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }
        $result = (new Invoice)->InvoiceSearch($id);
        $options = '';
        foreach($result as $key => $value) {
            $options .='<option value="'. $key .'">' . $value . '</option>';
        }
        echo $options;
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invoice($id)
    {
        $result = (new Invoice)->company()->find($id);
        if (!$result) {
            abort(401);
        }

        $customer = (new Customer)->getCustomerInfo($result->customer_id);
        $orderItems = (new InvoiceItems)->getInvoiceItems(['invoice_id' => $id]);
        return view('invoice.invoice', compact('id', 'customer', 'result', 'orderItems'));
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderPrint($id)
    {
        /*$result = (new Order)->find($id);

        if (!$result) {
            abort(401);
        }
        $companyId = loggedInCompanyId();
        $company = (new Company)->getCompanyInfo($companyId);
        $setting = '';
        //$setting = getCompanySetting();
        $printOptions = '';
        //$printOptions = explode(',', $setting->print_options);
        //$bank = Bank::find($result->bank_id);
        $bank = '';
        //$party = (new Account)->getAccountDetail($result->account_id);
        $party = (new Customer)->find($result->user_id);
        dd($result->toArray(),$party->toArray());

        $items = (new Invoice)->getInvoiceItemDetail($id, $result);
        $orderItems = $items['orderItem'];
        $taxes = $items['taxes'];

        return view('order.order_print', compact('id', 'party', 'result', 'orderItems',
            'company' , 'bank', 'taxes', 'setting', 'printOptions'));*/


        $result = (new Order)->getOrderDetail($id);
        if (!$result) {
            abort(404);
        }


        //$bank = (new Bank)->getBankService();
        $products = (new OrderProducts)->getProductsByOrderId($id);

        //$products = (new OrderProducts)->getProductsService();
        $orderItems = (new OrderProductSizes)->getInvoiceItems(['order_id' => $id]);

        $itemCountProductWise=[];
        foreach($products as $product){
            $count=0;
            foreach ($orderItems as $item){
                if($item['product_id']==$product['product_id']){
                    $count++;
                }
            }
            $itemCountProductWise[$product['product_id']]=$count;
        }

        $companyId = loggedInCompanyId();
        $company = (new Company)->getCompanyInfo($companyId);
        $party = (new Customer)->findByUserId($result->user_id);
        //dd($company,$party,$result->user_id);
        //dd($result->toArray(),$party->toArray(),$orderItems->toArray()  ,$company->toArray());



        // dd($items->toArray(),$result->toArray(),$products->toArray());
        //return view('order.edit', compact('result', 'items', 't', 'a' , 'products','itemCountProductWise'));
        return view('order.order_print', compact('id', 'result', 'orderItems', 'products','itemCountProductWise','company','party'));


    }

    /**
     * @param $id
     *
     * @return string
     */
    public function drop($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $result = (new Invoice)->company()->find($id);
        if (!$result) {
            abort(401);
        }

        try {
            (new Invoice)->drop($id);
            (new StockMaster)->deleteStock($id, 2, null);
            $response = ['status' => 1, 'message' => lang('messages.deleted', lang('invoice.invoice'))];

        } catch (\Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }
        // return json response
        return json_encode($response);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function generatePdfInvoice($id)
    {
        $result = (new Invoice)->company()->find($id);
        if (!$result) {
            abort(401);
        }

        $companyId = loggedInCompanyId();
        $company = (new Company)->getCompanyDetails($companyId);
        $setting = getCompanySetting();
        $bank = Bank::find($result->bank_id);
        $printOptions = explode(',', $setting->print_options);
        $account = (new Account)->getAccountDetail($result->account_id);
        $items = (new Invoice)->getInvoiceItemDetail($id, $result);
        $orderItems = $items['orderItem'];
        $taxes = $items['taxes'];
        $pdf = \PDF::loadView('invoice.invoice_print', [
            'id' => $id, 'party' => $account, 'result' => $result,
            'orderItems' => $orderItems, 'company' => $company,
            'bank' => $bank, 'pdf' => 1, 'setting' => $setting,
            'printOptions' => $printOptions, 'taxes' => $taxes
        ]);
        $pdf->setPaper('A4', 'portrait')->setWarnings(false);
        return $pdf->stream();
        //return view('invoice.invoice_print', compact('id', 'customer', 'result', 'orderItems'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function sendEmail($id = null)
    {
        $inputs = \Input::all();
        if (count($inputs) > 0) {
            $result = (new Invoice)->company()->find($id);
            if (!$result) {
                abort(401);
            }

            $account = (new Account)->getAccountDetail($result->account_id);
            if ($account->email1 == "") {
                return redirect()->route('invoice.index')
                    ->with('error', lang('invoice.customer_email_not_found'));
            }
            $account['message'] = $inputs['message'];
            return (new Invoice)->sendEmailToCustomer($id, $result, $account);
        } else {
            return view('invoice.send_email_modal', compact('id'));
        }
    }

    /**
     * method is used to view popup item detail
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invoiceItemDetail($id = null)
    {
        $items = [];
        $invoice = [];
        if($id) {
            $invoice = (new Invoice)->company()->find($id);
            $items = (new InvoiceItems)->getInvoiceItems(['invoice_id' => $id]);
            if (!$invoice) {
                abort(401);
            }
        }
        return view('invoice.invoice_item_detail_modal', compact('invoice', 'items'));
    }

    /**
     * method is used to set Sale Type
     */
    public function setSaleType($accountId)
    {
        $accountStateId  = (new Account)->getStateId($accountId);
        print_r($accountStateId->toArray());
        $companyStateId  = (new Company)->getStateId(loggedInCompanyId());
        if($accountStateId['state_id'] == $companyStateId['state_id']){
            return json_encode(['saleType' => 1]);
        }
        return json_encode(['saleType' => 2]);
    }
}
