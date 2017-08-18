<?php 
namespace App\Http\Controllers;
/**
 * :: Reports Controller ::
 * To manage reports.
 *
 **/

use App\Customer;
use App\GroupHead;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Order;
use App\Product;
use App\ProductGroup;
use App\SaleInvoice;
use App\SaleInvoiceItems;
use App\StockMaster;
use App\TransactionMaster;
use App\User;
use App\Bank;
use App\BankStatement;
use Session;
class ReportController extends Controller
{
	/**
	 * Display a listing of the resource.
	 * @return Response
	 */
	public function index()
	{
		$products = (new Product)->getProductsService();
		$group = (new ProductGroup)->getProductGroupService();
		$inputs = \Input::all();

		if(count($inputs) > 1) {
            Session::flash('inputs', $inputs);
        }

		$render = [];
		if (\Request::isMethod('post') && \Request::ajax())
		{
			unset($inputs['_token']);
			unset($inputs['page']);
			unset($inputs['keyword']);
			unset($inputs['perpage']);
			$render = (new Product)->getStockReport($inputs);
			if (count($render) > 0) {
                return view('reports.stock_summary_load_data', compact('render','inputs'));
            }
            else {
                return view('reports.stock_summary_load_data', compact('render','inputs'));
            }
        }
        return view('reports.stock-summary', compact('products', 'group'));
    }

	/**
	 * view cost report
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function costReport()
	{
		$products = (new Product)->getProductsService();
		$customer = (new Customer)->getCustomerService();
		$inputs = \Input::all();
        if(count($inputs) > 1) {
            Session::flash('inputs', $inputs);
        }
		if (\Request::isMethod('post') && \Request::ajax())
		{
			unset($inputs['_token']);
			unset($inputs['page']);
			unset($inputs['keyword']);
			unset($inputs['perpage']);
			$data = (new SaleInvoiceItems)->getCostReportItems($inputs);
			return view('reports.cost_report_load_data', compact('data', 'inputs'));
		}
		return view('reports.cost-report', compact('products', 'customer'));
	}

	/**
	 * view sale report
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function saleReport()
	{
		//$products = (new Product)->getProductsService();
		$customer = (new Customer)->getCustomerService();
		//$products = [];
		$inputs = \Input::all();

		/*if(isset($inputs['report_type'])) {
			session('report_type', $inputs['report_type']);
		}

		dump(session('report_type'));*/

        if(count($inputs) > 1) {
			//dd($inputs);
            Session::flash('inputs', $inputs);
        }
		if (\Request::isMethod('post') && \Request::ajax())
		{
			/*unset($inputs['_token']);
			unset($inputs['page']);
			unset($inputs['keyword']);
			unset($inputs['perpage']);*/

			// dd($inputs);
			$page = 1;
			if (isset($inputs['page']) && (int)$inputs['page'] > 0) {
				$page = $inputs['page'];
			}

			$perPage = 20;
			if (isset($inputs['perpage']) && (int)$inputs['perpage'] > 0) {
				$perPage = $inputs['perpage'];
			}

			$start = ($page - 1) * $perPage;


			$data = (new Order)->getOrdersNew($inputs, $start, $perPage);
			//dd($data->toArray());
			//$data = (new Order)->saleOrderReport($inputs);
			return view('reports.sale_report_load_data', compact('data', 'inputs', 'setting'));
		}

		return view('reports.sale-report', compact('customer', 'inputs'));
	}

	/**
	 * view bank statement report
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function bankStatementReport()
	{
		$banks = (new Bank)->getBankService();
		$groups = (new GroupHead)->getGroupHeadService();

		$inputs = \Input::all();
		if (\Request::isMethod('post') && \Request::ajax())
		{
			unset($inputs['_token']);
			unset($inputs['page']);
			unset($inputs['keyword']);
			unset($inputs['perpage']);

			$data = (new BankStatement)->bankStatementReport($inputs);
			return view('reports.bank_statement_report_load_data', compact('data', 'inputs'));
		}
		return view('reports.bank_statement_report', compact('banks', 'groups'));
	}

    /**
     * @return \Illuminate\Http\Response
     */
	public function stockReportPdf() 
	{
        ini_set('memory_limit', '-1');
        $inputs = Session::get('inputs');
        $render = (new Product)->getStockReport($inputs);
        reset($inputs);
        $main = view('reports.stock_summary_load_data', compact('render'));
        return generatePDF('reports.report_common', $main );
    }

    /**
     * @Return Generate Excel file
     */
    public function stockReportExcel() 
    {
        ini_set('memory_limit', '-1');
	    $inputs = Session::get('inputs');
	    $render = (new Product)->getStockReport($inputs);
        //reset($inputs);
        $main = view('reports.stock_summary_load_data', compact('render'));
        return generateExcel('reports.report_common', ['main' => $main], 'Stock-Summary');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function saleReportPdf() 
    {
        ini_set('memory_limit', '-1');
        $inputs = Session::get('inputs');
        $data = (new SaleInvoice)->saleInvoiceReport($inputs);
        reset($inputs);
        $main = view('reports.sale_report_load_data', compact('data'));
        return generatePDF('reports.report_common', $main );
    }

    /**
     * @Return Generate Excel file
     */
    public function saleReportExcel() {
        ini_set('memory_limit', '-1');
        $inputs = Session::get('inputs');
        $data = (new SaleInvoice)->saleInvoiceReport($inputs);
        reset($inputs);
        $main = view('reports.sale_report_load_data', compact('data'));
        return generateExcel('reports.report_common', ['main' => $main],'Sale-Report');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function costReportPdf() {
        ini_set('memory_limit', '-1');
        $inputs = Session::get('inputs');
        $data = (new SaleInvoiceItems)->getCostReportItems($inputs);
        reset($inputs);
        $main = view('reports.cost_report_load_data', compact('data'));
        return generatePDF('reports.report_common', $main );
    }

    /**
     * @return Generate Excel file
     */
    public function costReportExcel() {
        ini_set('memory_limit', '-1');
        $inputs = Session::get('inputs');
        $data = (new SaleInvoiceItems)->getCostReportItems($inputs);
        reset($inputs);
        $main = view('reports.cost_report_load_data', compact('data'));
        return generateExcel('reports.report_common', ['main' => $main], 'Cost-Report');
    }

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function accountStatement()
	{
		$inputs = \Input::all();
		if (\Request::isMethod('post') && \Request::ajax())
		{
			unset($inputs['_token']);
			unset($inputs['page']);
			unset($inputs['keyword']);
			unset($inputs['perpage']);
			unset($inputs['sort_action']);
			unset($inputs['sort_entity']);

			$data = (new TransactionMaster)->accountStatement($inputs);
			return view('reports.account_statement_load_data', compact('data', 'inputs'));
		}
		return view('reports.account-statement');
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function accountStatementGroupWise()
	{
		$inputs = \Input::all();
		if (\Request::isMethod('post') && \Request::ajax())
		{
			unset($inputs['_token']);
			unset($inputs['page']);
			unset($inputs['keyword']);
			unset($inputs['perpage']);
			unset($inputs['sort_action']);
			unset($inputs['sort_entity']);

			$data = (new TransactionMaster)->accountStatementGroupWise($inputs);
			$result = [];
			if(is_object($data)) {
				foreach($data as $detail) {
					if(!array_key_exists($detail->account_cr_id, $result) &&
						!array_key_exists($detail->account_dr_id, $result)) {
						if ($detail->cr_account_group == $inputs['account_group']) {
							$result[$detail->account_cr_id] = [
								'account' => $detail->cr_account,
								'amount_cr' => $detail->amount,
								'amount_dr' => 0,
							];
						} else {
							$result[$detail->account_dr_id] = [
								'account' => $detail->dr_account,
								'amount_cr' => 0,
								'amount_dr' => $detail->amount,
							];
						}
					} else {
						if ($detail->cr_account_group == $inputs['account_group']) {
							$amount = array_get($result, $detail->account_cr_id . '.amount_cr');
							array_set($result, $detail->account_cr_id . '.amount_cr', $amount + $detail->amount);
						} else {
							$amount = array_get($result, $detail->account_dr_id . '.amount_dr');
							array_set($result, $detail->account_dr_id . '.amount_dr', $amount + $detail->amount);
						}
					}
				}
			}
			//dd($result);
			return view('reports.account_group_statement_load_data', compact('result', 'inputs'));
		}
		return view('reports.account-group-statement');
	}
}