<?php

namespace App;
/**
 * :: Invoice Items Model ::
 * To manage Invoice Items CRUD operations
 *
 **/

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItems extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'invoice_items';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'product_id',
        'cgst',
        'cgst_amount',
        'sgst',
        'sgst_amount',
        'igst',
        'igst_amount',
        'quantity',
        'price',
        'manual_price',
        'total_price'
    ];

    public $timestamps = false;

    /**
     * @param $input
     * @param int $id
     * @return mixed
     *
     */
    public function store($input, $id = null)
    {
        $items = $input + [
            //'invoice_id'        => $input['invoice_id'],
            'product_id'        => $input['product'],
            'price'             => $input['price'],
            'manual_price'      => $input['manual_price'],
            'quantity'          => $input['quantity'],
        ];
        if ($id) {
            $this->find($id)->update($items);
            return $id;
        } else {
            $this->create($items);
        }
    }

    /**
     * Method is used to search total results.
     *
     * @param array $search
     *
     * @return mixed
     */
    public function getInvoiceItems($search = null)
    {
        $fields = [
            'invoice_items.*',
            'invoice_master.invoice_number',
            'invoice_master.invoice_date',
            'product_name',
            'product_code',
            'hsn_master.id as hsn_id',
            'hsn_master.hsn_code',
            'unit.id as unit_id',
            //'unit.name as unit',
            'unit.code as unit',
            'tax.name as tax_group',
            'tax.id as tax_id'
        ];
        $filter = 1; // default filter if no search
        if (is_array($search) && count($search) > 0) {
            $f1 = (array_key_exists('invoice_id', $search) && $search['invoice_id'] != "") ? " AND (invoice_id = " .
                addslashes(trim($search['invoice_id'])) . ")" : "";

            $filter .= $f1;
            return $this->leftJoin('invoice_master', 'invoice_master.id', '=', 'invoice_items.invoice_id')
                ->leftJoin('product', 'product.id', '=', 'invoice_items.product_id')
                ->leftJoin('tax', 'tax.id', '=', 'product.tax_id')
                ->leftJoin('hsn_master', 'hsn_master.id', '=', 'product.hsn_id')
                ->leftJoin('unit', 'unit.id', '=', 'product.unit_id')
                ->whereRaw($filter)
                ->get($fields);
        }
        return null;
    }

    /**
     * Method is used to search total results.
     *
     * @param array $search
     *
     * @return mixed
     */
    public function getInvoiceItem($search = null)
    {
        $fields = [
            'invoice_items.*',
            'invoice_master.invoice_number',
            'invoice_master.invoice_date',
            'product_name',
            'product_code',
            'hsn_master.id as hsn_id',
            'hsn_master.hsn_code',
            'unit.id as unit_id',
            'unit.code as unit',
            'tax.name as tax_group',
            'tax.id as tax_id'
        ];
        $filter = 1; // default filter if no search
        if (is_array($search) && count($search) > 0) {
            $f1 = (array_key_exists('id', $search) && $search['id'] != "") ? " AND (invoice_items.id = " .
                addslashes(trim($search['id'])) . ")" : "";

            $filter .= $f1;
            return $this->leftJoin('invoice_master', 'invoice_master.id', '=', 'invoice_items.invoice_id')
                ->leftJoin('product', 'product.id', '=', 'invoice_items.product_id')
                ->leftJoin('tax', 'tax.id', '=', 'product.tax_id')
                ->leftJoin('hsn_master', 'hsn_master.id', '=', 'product.hsn_id')
                ->leftJoin('unit', 'unit.id', '=', 'product.unit_id')
                ->whereRaw($filter)
                ->first($fields);
        }
        return null;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function dropItem($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * @param null $search
     * @return null
     */
    public function getCostReportItems($search = null)
    {
        $fields = [
            'invoice_items.*',
            'invoice.id as invoice_id',
            'invoice.invoice_number',
            'invoice.invoice_date',
            'invoice.company_id',
            'product.product_name',
            'product.product_code',
        ];

        $filter = 1;

        if(is_array($search) && array_key_exists('form-search', $search)) {
            if (is_array($search) && count($search) > 0) {
                $f1 = (array_key_exists('invoice_id', $search) && $search['invoice_id'] != "") ? " AND (sale_invoice_id = " .
                    addslashes(trim($search['invoice_id'])) . ")" : "";

                $f2 = (array_key_exists('product', $search) && $search['product'] != "") ? " AND (product.id = " .
                    addslashes(trim($search['product'])) . ")" : "";

                if (array_key_exists('from_date', $search) && $search['from_date'] != "" && $search['to_date'] == "") {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%Y-%m-%d")') . " = '" . dateFormat('Y-m-d', $search['from_date']) . "' ";
                }

                if (array_key_exists('from_date', $search) && $search['from_date'] != "" &&
                    array_key_exists('to_date', $search) && $search['to_date'] != ""
                ) {
                    $filter .= " and " . \DB::raw('DATE_FORMAT(invoice_date, "%Y-%m-%d")') . " between '" . dateFormat('Y-m-d', $search['from_date']) . "' and
                    '" . dateFormat('Y-m-d', $search['to_date']) . "'";
                }

                $filter .= $f1 . $f2;

                return $this->leftJoin('invoice', 'invoice.id', '=', 'invoice_items.invoice_id')
                            ->leftJoin('product', 'product.id', '=', 'invoice_items.product_id')
                            ->where('sale_invoice.company_id', loggedInCompanyId())
                            ->whereNull('invoice.deleted_at')
                            ->whereRaw($filter)
                            ->get($fields);
            }
        }

        return null;
    }

    /**
     * @param array $itemIds
     * @return mixed
     */
    public function drop($itemIds = [])
    {
        return $this->whereIn('id', $itemIds)->delete();
    }
}
