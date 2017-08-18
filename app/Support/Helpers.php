<?php
/**
 * :: Helper File ::
 * USed for manage all kind of helper functions.
 *
 **/
use App\Company;
use App\Setting;
use Illuminate\Database\Eloquent\Model;
use App\Unit;

/**
 * Method is used to debug statements.
 * @param array $post
 * @param int $die
 * @return Response
 */
function debug($post, $die = 0)
{
	echo '<pre>'; print_r($post);
	($die == 1) ? die : '';
}

/**
 * Trim whitespace from inputs
 *
 * @return bool
 */
function trimInputs()
{
	$inputs = \Input::all();
	array_walk_recursive($inputs, function (&$value) {
		$value = trim($value);
	});
	\Input::merge($inputs);
	return true;
}

/**
 * conversion utc time to country specific time zone depending upon which country user is belong to
 *
 * @param $utcDate
 * @param string $format
 * @return bool|string
 */
function convertToLocal($utcDate, $format = null)
{
	$currentTimezone = getCompanySettings('timezone');
	$dateFormat = getCompanySettings('datetime_format');
	if($currentTimezone !='') {
		$date = new \DateTime($utcDate, new DateTimeZone('UTC'));
		$date->setTimezone(new DateTimeZone($currentTimezone));
		return $date->format($dateFormat);
	} else {
		$date = new \DateTime($utcDate, new DateTimeZone('UTC'));
		return $date->format($dateFormat);
	}
}
/**
 * @param string $localDate
 * @param string $format
 * @return bool|string
 */
function convertToUtc($localDate = null, $format = null)
{
	$currentTimezone = getCompanySettings('timezone');
	$format = ($format == "") ? 'Y-m-d H:i:s' : $format;
	$localDate = ($localDate == "") ? date('Y-m-d H:i:s') : $localDate;
	$date = new \DateTime($localDate, new DateTimeZone($currentTimezone));
	$date->setTimezone(new DateTimeZone('UTC'));
	return $date->format($format);
}

/**
 * @param bool $withTime
 * @return bool|string
 */
function currentDate($withTime = false)
{
	$date = date('Y-m-d H:i:s');
	if (!$withTime) {
		$date = date('Y-m-d');
	}
	return $date;
}

/**
 * Method is used to convert date to
 * specified format
 *
 * @param string $format
 * @param date $date
 *
 * @return Response|String
 */
function dateFormat($format, $date)
{
	if (trim($date) != '') {
		if (trim($date) == '0000-00-00' || trim($date) == '0000-00-00 00:00') {
			return null;
		} else {
			$defaultFormats = [ 'd.m.Y', 'd-m-Y'];
			if(in_array($format, $defaultFormats)) {
				$format = getCompanySettings('datetime_format');
			}
			return date($format, strtotime($date));
		}
	}
	return $date;
}

/**
 * Method is used to get language phrases
 *
 * @param string $path
 * @param string $string
 * @return String
 **/
function lang($path = null, $string = null)
{
	$lang = $path;
	if (trim($path) != '' && trim($string) == '') {
		$lang = \Lang::get($path);
	} elseif (trim($path) != '' && trim($string) != '') {
		$lang = \Lang::get($path, ['attribute' => $string]);
	}
	return $lang;
}

/**
 * Method is used to return string in lower, upper or ucfirst.
 *
 * @param string $string
 * @param string $type L -> lower, U -> upper, UC -> upper character first
 * @return Response
 */
function string_manip($string = null, $type = 'L')
{
	switch ($type) {
		case 'U':
			return strtoupper($string);
			break;
		case 'UC':
			return ucfirst($string);
			break;
		case 'UCW':
			return ucwords($string);
			break;
		default:
			return strtolower($string);
			break;
	}
}

/**
 * @param bool $status
 * @param int $statusCode
 * @param string $message
 * @param array $result
 *
 * @return \Illuminate\Http\JsonResponse
 */
function apiResponse($status, $statusCode, $message, $errors = [], $result = [])
{
	$response = ['success' => $status, 'status' => $statusCode];

	if ($message != "") {
		$response['message'] = $message;
	}

	if (count($errors) > 0) {
		$response['errors'] = $errors;
	}

	if (count($result) > 0) {
		$response['result'] = $result;
	}
	return response()->json($response, $statusCode);
}


/**
 * @param bool $status
 * @param int $statusCode
 * @param string $message
 * @param string $url
 * @param array $errors
 * @return \Illuminate\Http\JsonResponse
 * @internal param array $result
 *
 */
function validationResponse($status, $statusCode, $message = null, $url = null, $errors = [])
{
	$response = ['success' => $status, 'status' => $statusCode];

	if ($message != "") {
		$response['message'] = $message;
	}

	if ($url != "") {
		$response['url'] = $url;
	}

	if (count($errors) > 0) {
		$response['errors'] = errorMessages($errors);
	}
	return response()->json($response, $statusCode);
}

/**
 * @param array $errors
 * @return array
 */
function errorMessages($errors = [])
{
	$error = [];
	foreach($errors->toArray() as $key => $value) {
		foreach($value as $messages) {
			$error[$key] = $messages;
		}
	}
	return $error;
}
/**
 * Method is used to create pagination controls
 *
 * @param int $page
 * @param int $total
 * @param int $perPage
 *
 * @return string
 */
function paginationControls($page, $total, $perPage = 20)
{
	$paginates = '';
	$curPage = $page;
	$page -= 1;
	$previousButton = true;
	$next_btn = true;
	$first_btn = false;
	$last_btn = false;
	$noOfPaginations = ceil($total / $perPage);

	/* ---------------Calculating the starting and ending values for the loop----------------------------------- */
	if ($curPage >= 10) {
		$start_loop = $curPage - 5;
		if ($noOfPaginations > $curPage + 5) {
			$end_loop = $curPage + 5;
		} elseif ($curPage <= $noOfPaginations && $curPage > $noOfPaginations - 9) {
			$start_loop = $noOfPaginations - 9;
			$end_loop = $noOfPaginations;
		} else {
			$end_loop = $noOfPaginations;
		}
	} else {
		$start_loop = 1;
		if ($noOfPaginations > 10)
			$end_loop = 10;
		else
			$end_loop = $noOfPaginations;
	}

	$paginates .= '<div class="col-sm-5 padding0 pull-left custom-martop">' .
		lang('common.jump_to') .
		'<input type="text" class="goto" size="1" />
					<button type="button" id="go_btn" class="go_button btn btn-default btn-xs paddingtop5"> <span class="fa fa-arrow-right"> </span> </button> ' .
		lang('common.pages') . ' ' .  $curPage . ' of <span class="_total">' . $noOfPaginations . '</span> | ' . lang('common.total_records', $total) .
		'</div> <ul class="pagination pagination-sm pull-right custom-martop">';

	// FOR ENABLING THE FIRST BUTTON
	if ($first_btn && $curPage > 1) {
		$paginates .= '<li p="1" class="disabled">
	    					<a href="javascript:void(0);">' .
			lang('common.first')
			. '</a>
	    			   </li>';
	} elseif ($first_btn) {
		$paginates .= '<li p="1" class="disabled">
	    					<a href="javascript:void(0);">' .
			lang('common.first')
			. '</a>
	    			   </li>';
	}

	// FOR ENABLING THE PREVIOUS BUTTON
	if ($previousButton && $curPage > 1) {
		$pre = $curPage - 1;
		$paginates .= '<li p="' . $pre . '" class="_paginate">
	    					<a href="javascript:void(0);" aria-label="Previous">
					        	<span aria-hidden="true">&laquo;</span>
				      		</a>
	    			   </li>';
	} elseif ($previousButton) {
		$paginates .= '<li class="disabled">
	    					<a href="javascript:void(0);" aria-label="Previous">
					        	<span aria-hidden="true">&laquo;</span>
				      		</a>
	    			   </li>';
	}

	for ($i = $start_loop; $i <= $end_loop; $i++) {
		if ($curPage == $i)
			$paginates .= '<li p="' . $i . '" class="active"><a href="javascript:void(0);">' . $i . '</a></li>';
		else
			$paginates .= '<li p="' . $i . '" class="_paginate"><a href="javascript:void(0);">' . $i . '</a></li>';
	}

	// TO ENABLE THE NEXT BUTTON
	if ($next_btn && $curPage < $noOfPaginations) {
		$nex = $curPage + 1;
		$paginates .= '<li p="' . $nex . '" class="_paginate">
	    					<a href="javascript:void(0);" aria-label="Next">
					        	<span aria-hidden="true">&raquo;</span>
					      	</a>
	    			   </li>';
	} elseif ($next_btn) {
		$paginates .= '<li class="disabled">
	    					<a href="javascript:void(0);" aria-label="Next">
					        	<span aria-hidden="true">&raquo;</span>
					      	</a>
	    			   </li>';
	}

	// TO ENABLE THE END BUTTON
	if ($last_btn && $curPage < $noOfPaginations) {
		$paginates .= '<li p="' . $noOfPaginations . '" class="_paginate">
	    					<a href="javascript:void(0);">' .
			lang('common.last')
			. '</a>
	    			   </li>';
	} elseif ($last_btn) {
		$paginates .= '<li p="' . $noOfPaginations . '" class="disabled">
	    					<a href="javascript:void(0);">' .
			lang('common.last')
			. '</a>
			   		   </li>';
	}

	$paginates .= '</ul>';

	return $paginates;
}

/**
 * @param $value
 * @param string $seprator
 *
 * @return string
 */
function numberFormat($value, $seprator = ',')
{
	return ($value > 0) ? number_format($value, 2, '.', $seprator) : '0.00';
}

/**
 * @param $index
 * @param $page
 * @param $perPage
 * @return mixed
 */
function pageIndex($index, $page, $perPage)
{
	return (($page - 1) * $perPage) + $index;
}

/**
 * @param $t
 * @return string
 */
function reportType($t)
{
	switch ($t) {
		case 2:
			return 'xlsx';
			break;
		case 3:
			return 'csv';
			break;
		default:
			return 'xls';
			break;
	}
}

/**
 * @return bool
 */
function isSystemAdmin()
{
	return (\Auth::user()->id == 1) ? true : false;
	
}

function isAdmin() 
{
   return (\Auth::user()->role_id == 1) ? true : false;
}

/**
 * @return bool
 */
function isSuperAdmin()
{
	if(\Auth::user()) {
		return (\Auth::user()->is_super_admin == 1) ? true : false; 
	}
}

/**
 * @return null
 */
function authUserId()
{
	$id = null;
	if (\Auth::check()) {
		$id = \Auth::user()->id;
	}

	return $id;
}

function authUser()
{
	$user = null;
	if (\Auth::check()) {
		$user = \Auth::user();
	}
	return $user;
}

function getSuperAdminRoutes()
{
	return [
		'company.index',
		'company.create',
		'company.store',
		'company.edit',
		'company.update',
		'company.paginate',
		'company.toggle',
		'company.action',
		'menu.index',
		'menu.create',
		'menu.store',
		'menu.edit',
		'menu.update',
		'menu.paginate',
		/*'currency.index',
		'currency.create',
		'currency.store',
		'currency.edit',
		'currency.update',
		'currency.paginate',
		'currency.action',
		'currency.toggle',
		'datetime-format.index',
		'datetime-format.create',
		'datetime-format.store',
		'datetime-format.edit',
		'datetime-format.update',
		'datetime-format.paginate',
		'datetime-format.toggle',
		'datetime-format.drop',*/
	];
}

function getCustomerRoutes()
{
	return [
		'dashboard',
		'customer-order.index',
		'customer-order.create',
		'customer-order.store',
		'customer-order.edit',
		'customer-order.update',
		'customer-order.paginate',
		'customer-order.item-detail',
		'customer-order.toggle',
		'customer-order-item.drop',
		'customer-order-item.edit',
		'customer-order.drop',
		'customer-order.add-more',
		'customer-order.invoice',
		'customer-order.invoice-print',
		'customer-order.invoice-pdf',
		'products.get-cost',
		'setting.manage-account'
	];
}
function getCustomerRoutesNotAllowed()
{
	return [
		'customer.create',
	];
}
/**
 * PHP age Calculator
 * Calculate and returns age based on the date provided by the user.
 * @param   date of birth('Format:yyyy-mm-dd').
 * @return  age based on date of birth
 */
function ageCalculator($dob) {
	if(!empty($dob)){
		$birthDate = new DateTime($dob);
		$today   = new DateTime('today');
		$age = $birthDate->diff($today)->y;
		return $age;
	} else {
		return 0;
	}
}

/**
 * @param null $type
 * @return array
 */
function freightType($type = null)
{
	$types = [
		'1' => 'To Pay',
		'2' => 'Paid',
	];

	if ($type != "") {
		return $types[$type];
	}
	return $types;
}

/**
 * @param null $type
 * @return array
 */
function purchaseType($type = null)
{
	$types = [
    '1' => 'GST'
];

	if ($type != "") {
		return $types[$type];
	}
	return $types;
}

/**
 * @param $number
 * @param bool|true $isINR
 * @param bool $show
 *
 * @return bool|string
 */
function numberToWord($number, $isINR = true, $show = false)
{
    $hyphen      = ' ';
    $conjunction = ' ';
    $separator   = ' ';
    $negative    = ' ';
    $decimal     = ' ';
    $decimalPlace = ($isINR) ? ' paisa ' : ' cent ';
    $showCent     = ($show) ? ' cent ' : '';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

	$baseNumber = $number;
	if (!is_numeric($number)) {
        return false;
    }
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }
    if ($number < 0) {
        return $negative . numberToWord(abs($number));
    }
    $string = $fraction = null;
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    $string = '';
    switch (true) {
        case $number < 21:
            $string .= $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . numberToWord($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = numberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= numberToWord($remainder);
            }
            break;
    }

	//dd($fraction);
    if (null !== $fraction && is_numeric($fraction)) {
        //$string .= $decimal;
        //$words = array();
		$number = $fraction;
		$string .= ' and ';
		$m = numberFormat($baseNumber, "");
		$k = explode('.', $m);
		if (isset($k[1]) && $k[1] > 0) {
			$number = $k[1];
		}
        switch (true) {
	        case $number < 21:
	            $string .= $dictionary[$number];
	            break;
	        case $number < 100:
	            $tens   = ((int) ($number / 10)) * 10;
	            $units  = $number % 10;
				$string .= $dictionary[$tens];
	            if ($units) {
	                $string .= $hyphen . $dictionary[$units];
	            }
	            break;
	        case $number < 1000:
	            $hundreds  = $number / 100;
	            $remainder = $number % 100;
	            $string .= $dictionary[$hundreds] . ' ' . $dictionary[100];
	            if ($remainder) {
	                $string .= $conjunction . numberToWord($remainder);
	            }
	            break;
	        default:
	            $baseUnit = pow(1000, floor(log($number, 1000)));
	            $numBaseUnits = (int) ($number / $baseUnit);
	            $remainder = $number % $baseUnit;
	            $string .= numberToWord($numBaseUnits) . ' ' . $dictionary[$baseUnit];
	            if ($remainder) {
	                $string .= $remainder < 100 ? $conjunction : $separator;
	                $string .= numberToWord($remainder);
	            }
	            break;
	    }

	    $string .= $decimalPlace;
        /*foreach (str_split((string) $fraction) as $number) {
        	$words[] = $dictionary[$number];
        }*/
        //$string .= ' and ';
        //$string .= $dictionary[$fraction] . $decimalPlace;
        //$string .= implode(' ', $words);
    }
    return ucfirst($string. $showCent);
}

/**
 * @param $array1
 * @param $array2
 * @return array
 */
function array_merge_keys($array1, $array2) {
	$keys = array_merge(array_keys($array1), array_keys($array2));
	$vals = array_merge($array1, $array2);
	return array_combine($keys, $vals);
}

/**
 * @param $menuRute
 * @return bool
 */
function hasMenuRoute($menuRute)
{
	if (authUser() && authUser()->id == 1) {
		return true;
	} else {
		$permissionResult	= getUserPermission();
		return (in_array($menuRute, $permissionResult)) ? true : false;
	}
}

/**
 * @param $number
 * @return string
 */
function paddingLeft($number)
{
	return str_pad($number, 2, "0", STR_PAD_LEFT);
}

/**
 * @param null $code
 * @param bool|false $isInvoice
 * @return array
 */
function getStatusNameByCode($code = null, $isInvoice = false, $selectHeading = false)
{
	$statusArray = [
		'A' => lang('products.a'),
		'B' => lang('products.b'),
		'C' => lang('products.c'),
		'D' => lang('products.d'),
		'E' => lang('products.e'),
		'F' => lang('products.f'),
		'G' => lang('products.g'),
		//0 => lang('messages.pending'),
		//1 => lang('messages.in_progress'),
		//2 => lang('messages.completed'),
	];

	if ($isInvoice) {
		//$statusArray = $statusArray + [3 => lang('messages.dispatched')];
	}

	if($code !== null) {
		//return $statusArray[$code];
	}

	if($selectHeading){
		$statusArray = ['' => '-Dimension-'] + $statusArray;
	}
	return $statusArray;
}

/**
 * @param null $month
 * @return array
 */
function getMonths($month = null)
{

	$months = [
		1	=>	'January',
		2	=>	'February',
		3	=>	'March',
		4	=>	'April',
		5	=>	'May',
		6	=>	'June',
		7	=>	'July',
		8	=>	'August',
		9	=>	'September',
		10	=>	'October',
		11	=>	'October',
		12	=>	'December'
	];
	if ($month != "") {
		return $months[$month];
	}
	return ['' => '-Select Month-'] + $months;
}

/**
 * @param $start
 * @param $end
 * @return array
 */
function getYear($start, $end)
{
	$years = [];
	for($i =$start; $i <=$end; $i++) {
		$years[$i] = $i;
	}
	return ['' => '-Select Year-'] + $years;

}

/**
 * @param $action
 *
 * @return int
 */
function sortAction($action)
{
	$sortAction = 0;
	if($action != "") {
		if ($action == 0) {
			$sortAction = 1;
		} elseif ($action == 1) {
			$sortAction = 2;
		} else {
			$sortAction = 1;
		}
		//$sortAction = ((int)$action === 0) ? 1 : ((int)$action === 1) ? 2 : 3; 
	}
	return $sortAction;
}

/**
 * @param $icon
 * @return mixed
 */
function sortIcon($icon)
{
	$iconArray = [
		'0' => 'fa fa-sort',
		'2' => 'fa fa-sort-up',
		'1' => 'fa fa-sort-down',
	];

	$icon = sortAction($icon);
	return $iconArray[$icon];
}
/**
 * @param $action
 *
 * @return int
 */
function getReportType($reportType = null)
{
	$reportArray = [
		1	=>	'Date',
		2	=>	'Month',
		3	=>	'Year',
	];
	if ($reportType != "") {
		return $reportArray[$reportType];
	}
	return $reportArray;
}


/**
 * @param null $type
 * @return int
 * @internal param $action
 *
 */
function getStockType($type = null)
{
	$typeArray = [
		1	=>	'Receive',
		2	=>	'Damage',
	];
	if ($type != "") {
		return $typeArray[$type];
	}
	return $typeArray;
}

function getMonthDefaultValue($type = null)
{
    $typeArray = [
        7	=>	'Jul',
        8	=>	'Aug',
        9	=>	'Sep',
        10	=>	'Oct',
        11	=>	'Nov',
        12	=>	'Dec',
        1	=>	'Jan',
        2	=>	'Feb',
        3	=>	'Mar',
        4	=>	'Apr',
        5	=>	'May',
        6	=>	'Jun',
    ];
    if ($type != "") {
        return $typeArray[$type];
    }
    return $typeArray;
}

/**
 * @param $view
 * @param array $params
 * @return \Illuminate\Http\Response
 */
function generatePDF( $view, $data ) {

    $pdf = \PDF::loadView( $view, ['main' => $data] );
    return $pdf->stream();
}

/**
 * @param $view
 * @param $data
 * @param string $filename
 */
function generateExcel($view, $data, $filename = 'file') 
{
    Excel::create($filename, function($excel) use ($view, $data) {
        $excel->sheet('Sheet', function($sheet) use ($view, $data){
            $sheet->loadView($view, $data);
        });
    })->export('xls');
}

/**
 * @param $banks
 * @return array
 */
function getFirstBankFromDropdown($banks) {
	$result = [];
	if( count($banks) > 0) {
		foreach( $banks as $key => $bankName ) {
			if($key != '') {
				$result = [ 'id' => $key, 'bank_name' => $bankName ] ;
				break;
			}
		}
	}
	return $result;
}

/**
 * @param Model $model
 * @param $maxRows
 * @return bool
 */
function isDemoVersionExpired($model, $maxRows = 5)
{
	$rows = (new $model)->company()->withTrashed()->count();
	if( $rows >= $maxRows ) {
		return true;
	}
}

/**
 * @param $routeName
 * @return array
 */
function getModelByRouteName($routeName) {
	$result = [];
	$routes = [
		[
			'route_name' => 'unit.create',
			'model' => 'App\Unit',
			'rows' => 5,
			'redirect_route' => 'unit.index'
		],
		[
			'route_name' => 'financial-year.create',
			'model' => 'App\FinancialYear',
			'rows' => 1,
			'redirect_route' => 'financial-year.index'
		],
		[
			'route_name' => 'product-group.create',
			'model' => 'App\ProductGroup',
			'rows' => 5,
			'redirect_route' => 'product-group.index'
		],
		[
			'route_name' => 'products.create',
			'model' => 'App\Product',
			'rows' => 5,
			'redirect_route' => 'products.index'
		],
		[
			'route_name' => 'customer.create',
			'model' => 'App\Customer',
			'rows' => 100,
			'redirect_route' => 'customer.index'
		],
		[
			'route_name' => 'supplier.create',
			'model' => 'App\Supplier',
			'rows' => 5,
			'redirect_route' => 'supplier.index'
		],
		[
			'route_name' => 'supplier-order.create',
			'model' => 'App\SupplierOrder',
			'rows' => 10,
			'redirect_route' => 'supplier-order.index'
		],
		[
			'route_name' => 'sale-order.create',
			'model' => 'App\SaleOrder',
			'rows' => 10,
			'redirect_route' => 'sale-order.index'
		],
		[
			'route_name' => 'sale-invoice.create',
			'model' => 'App\SaleInvoice',
			'rows' => 10,
			'redirect_route' => 'sale-invoice.index'
		],
	];
	foreach( $routes as $route ) {
		if($route['route_name'] == $routeName) {
			$result = [ 'model' => $route['model'], 'redirect_url' => $route['redirect_route'], 'rows' => $route['rows'] ];
			break;
		}
	}
	return $result;
}


/**
 * @param $key
 * @return mixed
 */
function getCompanySettings($key) {
	$companySettings = (new Setting)->getSettingService();
	$result = [
		'currency' =>    ( $companySettings['currency_symbol'] != '')?$companySettings['currency_symbol']:'Rs',
		'datetime_format' =>  ( $companySettings['date_time_format'] != '')?$companySettings['date_time_format']:'d-m-Y',
		'timezone' =>  ($companySettings['timestamp'] != '')?$companySettings['timestamp']:'Asia/Kolkata',
	];

	if(array_key_exists($key, $result)) {
		return $result[$key];
	}
}

/**
 * @return bool
 */
function isFullVersion()
{
	$record = (new Company)->where('company.id', loggedInCompanyId())->first();
	if(count($record) > 0) {
		return ($record->is_full_version == 1)?true:false;
	}
	return false;
}

function getSizeName($size_id)
{

	$size_masterId = (new \App\ProductSizes)->where('id', $size_id)->first(['size_master_id']);

	if(count($size_masterId) > 0) {
		$sizeName=(new \App\Size)->where('id',$size_masterId['size_master_id'])->first(['name']);
		//dd($sizeName,$size_masterId->toArray());
		if($sizeName) {
			return $sizeName['name'];
		}
	}
	return false;
}
function getTaxPercentage($tax_id)
{

	$tax_masterId = (new \App\Tax())->where('id', $tax_id)->where('status', 1)->first();
	//dd($tax_masterId);

	if(count($tax_masterId) > 0) {
		$taxArray=[
			'tax_name' => $tax_masterId->name,
			'cgst_rate'=> $tax_masterId->cgst_rate,
			'sgst_rate'=> $tax_masterId->sgst_rate,
			'igst_rate'=> $tax_masterId->igst_rate,
		];
		//dd($sizeName,$size_masterId->toArray());

			return $taxArray;
	}
	return false;
}

function getSizePrice($size_id)
{

	$size_price = (new \App\ProductCost())->where('size_id', $size_id)->first(['price']);

	if($size_price) {
		return $size_price->price;
	}
	return false;

}
function getUsername($user_id)
{

	$name = (new \App\Customer())->where('user_id', $user_id)->first(['customer_name']);

	if($name) {
		return $name->customer_name;
	}
	return false;

}

/**
 * @param $value
 * @return float
 */
function getRoundedAmount($value)
{
	$setting = getCompanySetting();
	if ($setting->round_off_type == 1) {
		$decimal = explode('.', $value);
		if (isset($decimal[1]) && $decimal[1] == 5) {
			return $value;
		} else {
			return round($value, 2);
		}
	} else {
		$decimal = explode('.', $value);
		if (isset($decimal[1]) && $decimal[1] == 5) {
			return $value;
		} else {
			return round($value);
		}
	}
}

function orderNumberInc($orderNumber){
	$number = substr($orderNumber, strrpos($orderNumber, '/') + 1);
	$incNumber = paddingLeft(substr($orderNumber, strrpos($orderNumber, '/') + 1) + 1);
	return $text = str_replace($number, $incNumber, $orderNumber);
}

function indianFormat($num)
{
	$pos = strpos((string)$num, ".");
	if ($pos === false) {
		$decimalpart="00";
	}
	if (!($pos === false)) {


		/* my custom code */
		$decimalpart= substr($num, $pos+1, 10);
		$num = substr($num, 0, $pos);
	}

	if(strlen($num)>3 & strlen($num) <= 12)
	{
		$last3digits = substr($num, -3 );
		$numexceptlastdigits = substr($num, 0, -3 );
		$formatted = makeComma($numexceptlastdigits);
		$stringtoreturn = $formatted.",".$last3digits.".".$decimalpart ;
	}
	elseif(strlen($num)<=3){
		$stringtoreturn = $num.".".$decimalpart ;
	}
	elseif(strlen($num)>12)
	{
		$stringtoreturn = number_format($num, 2);
	}

	if(substr($stringtoreturn,0,2)=="-,"){
		$stringtoreturn = "-".substr($stringtoreturn, 2);
	}

	/*//$val = (string) $stringtoreturn;
    $val = (float) $temp;
     echo $val; die;
    $e = explode('.', $val);
    $p = $e[1] * 10; */

	return $stringtoreturn;

	//return $stringtoreturn;
}

function makeComma($input){
	// This function is written by some anonymous person - I got it from Google
	if(strlen($input)<=2)
	{ return $input; }
	$length=substr($input,0,strlen($input)-2);
	$formatted_input = makeComma($length).",".substr($input,-2);
	return $formatted_input;
}




function formatIndianWords($number) {
	$number = (float)$number;
	$decimal = round($number - ($no = floor($number)), 2) * 100;
	$hundred = null;
	$digits_length = strlen($no);
	$i = 0;
	$str = array();

	$words = array(
		0 => '',
		1 => 'one',
		2 => 'two',
		3 => 'three',
		4 => 'four',
		5 => 'five',
		6 => 'six',
		7 => 'seven',
		8 => 'eight',
		9 => 'nine',
		10 => 'ten',
		11 => 'eleven',
		12 => 'twelve',
		13 => 'thirteen',
		14 => 'fourteen',
		15 => 'fifteen',
		16 => 'sixteen',
		17 => 'seventeen',
		18 => 'eighteen',
		19 => 'nineteen',
		20 => 'twenty',
		30 => 'thirty',
		40 => 'forty',
		50 => 'fifty',
		60 => 'sixty',
		70 => 'seventy',
		80 => 'eighty',
		90 => 'ninety'
	);

	$digits = array('', 'hundred', 'thousand', 'lakh', 'crore', 'arab');

	while( $i < $digits_length ) {
		$divider = ($i == 2) ? 10 : 100;
		$number = floor($no % $divider);
		$no = floor($no / $divider);
		$i += $divider == 10 ? 1 : 2;
		if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[ floor($number / 10) * 10 ].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
		} else $str[] = null;
	}
	return [
		'prefix'	=> $str,
		'decimal' => $decimal,
		'words'		=> $words
	];
}

function getIndianCurrency($number)
{
	if($number > 1000000000) {
		throw new Exception('Number is too large.');
	}
	//echo $number; die;
	$paise = '';
	$result  = formatIndianWords($number);
	$str = $result['prefix'];
	$decimal = $result['decimal'];
	$words = $result['words'];


	$Rupees = implode('', array_reverse($str));

	//$paise = ($decimal) ? "" . ($words[$decimal / 10] . " " . $words[$decimal % 10 ]) . ' Paise' : '';
	if($decimal !='') {
		$result = formatIndianWords($decimal);
		$paise = isset($result['prefix'][0]) ? $result['prefix'][0] . ' Paise' : '';
	}
	// echo $decimal; die;

	$result =  ($Rupees ? $Rupees . 'Rupees ' : '') . $paise ;
	return ucwords($result);
}



//echo getIndianCurrency(85756);

