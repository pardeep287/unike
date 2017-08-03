<?php

/**
 * @Author Pardeep Verma
 * @Created_at 17/4/2017
 */
namespace App\Http\Controllers\Api\V1;
use App\Customer;
use App\Http\Controllers\Controller;
use App\NotificationLog;
use App\QuotationMaster;
use App\QuotationItem;
use App\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use League\Flysystem\Exception;
use Illuminate\Support\Facades\Mail;
class QuotationController extends Controller
{



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( Request $request ){
        //dd('im in');
        //return apiResponse(false, 406, "", 'i am in');
        try{
            \DB::beginTransaction();
            $inputs = $request->all();
            //dd($inputs);

            /*if(array_key_exists('phone', $inputs)) {
                if($inputs['phone'] == '') {
                    unset($inputs['phone']);
                }
            }*/

            $validator = ( new QuotationMaster)->validate( $inputs, null, false, true);
            if( $validator->fails() ) {
                return apiResponse(false, 406, "", errorMessages($validator->messages()));
            }

            $revised_id = NULL;

            if( array_key_exists('date', $inputs)) {
                $date = dateFormat('Y-m-d', $inputs['date']);
                unset($inputs['date']);
                $inputs = $inputs + ['date' => $date];
            }


            $quotationData = [
                    'revised_id'        => $revised_id,
                    'customer_id'       => $inputs['customer_id'],
                    'user_id'           => $inputs['user_id'],
                    'quotation_number'  => $inputs['quotation_number'],
                    'date'              => $inputs['date'],
                    'remarks'           => isset($inputs['remarks'])?$inputs['remarks']:'',
                    'status'            => isset($inputs['status'])?$inputs['status']:1,
                    'ismail_Sent'       => 0,
                    'is_active'         => 1,
                    ];

            $id = (new QuotationMaster)->store($quotationData);


            if($id)
            {
                $total = 0;
                $itemCounts= count($inputs['description']);
                for ($x = 0; $x < $itemCounts; $x++) {
                    $quotationItemArray  =
                        [
                            'quotation_id' =>  $id,
                            'description' => $inputs['description'][$x],
                            'price' => $inputs['price'][$x],

                        ];
                    (new QuotationItem)->store($quotationItemArray , null, false);

                    $total += $inputs['price'][$x];
                }

                $update = [
                        'total_amount'      => $total
                    ];
                (new QuotationMaster)->quotationOrderUpdate($update, $id);


                \DB::commit();
                //$route = route('sale-order.index');
                $lang =  lang('quotation.create');
                
                return apiResponse(true, 200, $lang);
            }
            else
            {
                return apiResponse(false, 207, lang('messages.server_error'));
            }

        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function listQuotation(Request $request){
        try {
            //dd($request->url(),$request->path(),$request->method(), $request);
            $inputs = $request->all();
            $result = [];
            $quotations = (new QuotationMaster)->getQuotation($inputs);
            if(count($quotations) == 0) {
                return apiResponse(false, 404, lang('messages.not_found', lang('quotation.quotation')));
            }
            //dd($quotations->toArray());

            foreach ($quotations as $quotation) {
                $result[] = [
                    'quotation_id'      => $quotation->id,
                    'customer_id'       => $quotation->customer_id,
                    'user_id'           => $quotation->user_id,
                    'customer_name'     => $quotation->customer_name,
                    'quotation_number'  => ($quotation->status == 2)?$quotation->quotation_number. '-'. $quotation->revised_number:$quotation->quotation_number,
                    'date'              => ($quotation->date == '')?null:dateFormat('d-m-Y', $quotation->date),
                    'remarks'           => $quotation->remarks,
                    'status'            => getStatusNameByCode($quotation->status),
                    'is_emailsent'      => $quotation->is_emailsent,
                    //'description'       => $quotation->description,
                    //'price'             => $quotation->price,
                    'total_amount'      => $quotation->total_amount,
                    'revised_id'        => $quotation->revised_id,
                    'is_active'         => $quotation->is_active,

                ];
            }
            return apiResponse(true, 200 , null, [], $result);
        }
        catch (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }
    
    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getQuotationDetail( $id )
    {
        try{
            $quotationDetail = (new QuotationMaster)->findById($id);

            //dd($quotationDetail->toArray());
            if( count($quotationDetail) > 0 ) {
                if($quotationDetail[0]->user_id == authUserId() || isAdmin()) {
                    $result = [
                        'id' => $quotationDetail[0]->id,
                        'revised_id' => $quotationDetail[0]->revised_id,
                        'customer_id' => $quotationDetail[0]->customer_id,
                        'user_id' => $quotationDetail[0]->user_id,
                        'customer_name' => $quotationDetail[0]->customer_name,
                        'quotation_number' => $quotationDetail[0]->quotation_number,
                        'date' => ($quotationDetail[0]->date == '') ? null : dateFormat('d-m-Y', $quotationDetail[0]->date),
                        'total_amount' => $quotationDetail[0]->total_amount,
                        'remarks' => $quotationDetail[0]->remarks,
                        'status' => getStatusNameByCode($quotationDetail[0]->status),
                        'is_emailsent' => $quotationDetail[0]->is_emailsent ,
                    ];

                    $quotationItemDetail = (new QuotationItem)->findById($id);
                    //dd($result,$quotationItemDetail->toArray());
                    if( count($quotationItemDetail) > 0 ) {
                        $items = [];
                        foreach ($quotationItemDetail as $item) {
                            $items[] = [
                                'id' => $item->id,
                                'description' => $item->description,
                                'price' => $item->price,
                            ];

                        }
                        $result['quotation_items'] = $items;
                    }

                    return apiResponse(true, 200, null, [], $result);
                }
                else {
                    return apiResponse(false, 404, lang('auth.customer_not_accessible'));
                }
            }
            else {
                return apiResponse(false, 404, lang('messages.not_found', lang('quotation.quotation_details')));
            }
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function editQuotation()
    {
        $inputs = Input::all();
        //dd($inputs);
        $validator = ( new QuotationMaster)->validate( $inputs , null , true , false);
        //$validator = (new SaleOrder)->validateSaleOrder($inputs, null, true, true);
        if ($validator->fails()) {
            return apiResponse(false, 406, errorMessages($validator->messages()));
        }

        try {
            \DB::beginTransaction();
            /* Check if the order exists */

            $QuotationId = $inputs['id'];
            $insertArray = [];
            $record = QuotationMaster::find($QuotationId);
            if($record['is_active'] != 1){
                return apiResponse(false, 406, 'Please Check Quotation revision exists');
            }
            if (count($record) == 0) {
                return apiResponse(false, 406, 'Quotation order does not exists');
            }
            /* update the record */

            /*User Exists*/
        
            if($inputs['user_id'] == authUserId() || isAdmin()) {

                $userRecord = User::find($inputs['user_id']);
                if(count($userRecord) > 0){
                    $user_id = $inputs['user_id'];
                }
                else{
                    return apiResponse(false, 406, 'User Does Not Exists');
                }
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }

            /*Date Formatting*/
            $Date = dateFormat('Y-m-d H:i:s', $inputs['date'] . ' ' . date('H:i:s'));
            //unset($inputs['date']);
            //$inputs = $inputs + ['date' => $Date];

            /*Customer Exists*/
            $customerRecord = Customer::find($inputs['customer_id']);
            if(count($customerRecord) > 0){
                    $customer_id = $inputs['customer_id'];
            }
            else{
                return apiResponse(false, 406, 'Customer Does Not Exists');
            }

            $quotationUpdateArray = [
                //'revised_id'      =>  null ,
                'customer_id'       =>  $customer_id,
                'user_id'           =>  $user_id,
                //'quotation_number'=>  $inputs['quotation_number'],
                //'total_amount'    =>  $total_amount,
                'date'              =>  convertToUtc($Date),
                'remarks'           =>  $inputs['remarks'],
                //'status'          =>  0,
                //'is_emailsent'    =>  0,
                    ];
            //dd($inputs,$quotationUpdateArray);
            (new QuotationMaster)->store($quotationUpdateArray, $QuotationId);

            $deletedItems = [];
            $insertArray = [];
            $updateArray = [];
            $total_amount= 0;

            /* update Quotation order items */
            if(count($inputs['item_id']) > 0) {

                foreach ($inputs['item_id'] as $key => $itemId) {

                    if ($itemId != "" && $inputs['price'][$key] != "") {
                        //$saleOrder = SaleOrder::find($saleOrderId);
                        if (isset($inputs['item_id'][$key]) && (int)$inputs['item_id'][$key] < 1) {
                            /* insert a new item */

                            $insertArray[] = [
                                'quotation_id'  => $QuotationId,
                                'description'   => $inputs['description'][$key],
                                'price'         => $inputs['price'][$key],
                            ];
                            $total_amount += $inputs['price'][$key];
                        } else {
                            /* QuotationOrderItem */
                            $quotationItemId = $inputs['item_id'][$key];

                            $total_amount += $inputs['price'][$key];
                            $updateArray = [
                                //'id'          => $quotationItemId,
                                'quotation_id'  => $QuotationId,
                                'description'   => $inputs['description'][$key],
                                'price'         => $inputs['price'][$key],
                            ];
                            (new QuotationItem)->where('id', $quotationItemId)->where('quotation_id', $QuotationId)->update($updateArray);

                            if (isset($inputs['deleted_item_id'][$key]) && $inputs['deleted_item_id'][$key] > 0) {
                                $item = (new QuotationItem)->where('id', $inputs['deleted_item_id'][$key])->where('quotation_id', $QuotationId)->first();
                                //dd($item->toArray());
                                if (count($item) > 0) {
                                    $deletedItems[] = $inputs['deleted_item_id'][$key];
                                    $total_amount -= $item['price'];
                                }
                            }
                        }
                    }
                }
            }
           // dd($total_amount, $updateArray, $insertArray ,$deletedItems);

            /* insert */
            if (count($insertArray) > 0) {
                (new QuotationItem)->store($insertArray, null, true);
                $update = [
                        'total_amount'      => $total_amount,
                ];
                (new QuotationMaster)->quotationOrderUpdate($update, $QuotationId);
            }

            if(count($updateArray) > 0) {
                $update = [
                    'total_amount'      => $total_amount,
                ];
                (new QuotationMaster)->quotationOrderUpdate($update, $QuotationId);
            }

            // delete products
            if (count($deletedItems) > 0) {
                (new QuotationItem)->drop($deletedItems);
                $update = [
                    'total_amount'      => $total_amount,
                ];
                (new QuotationMaster)->quotationOrderUpdate($update, $QuotationId);
            }
            \DB::commit();
            return apiResponse(true, 200, lang('messages.updated', lang('quotation.quotation')));
        }

        catch
        (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


    public function quotationNumber()
    {
        try{
            $number = (new QuotationMaster)->getQuotationNumber();
            $result = [
                'quotation_number' => $number,
            ];
            return apiResponse(true, 200, null, [], $result);
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }

    }

    /**
     * @param null $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function viewQuotationPdf($id = null)
    {
        try{
            $quotationDetail = (new QuotationMaster)->findById($id ,true);
            if( count($quotationDetail) > 0 ) {
                if ($quotationDetail[0]->user_id == authUserId() || isAdmin()) {


                    $result = [
                        'id' => $quotationDetail[0]->id,
                        'revised_id' => $quotationDetail[0]->revised_id,
                        'customer_id' => $quotationDetail[0]->customer_id,
                        'user_id' => $quotationDetail[0]->user_id,
                        'customer_name' => $quotationDetail[0]->customer_name,
                        'customer_mobile' => $quotationDetail[0]->mobile_no,
                        'customer_contact_person' => $quotationDetail[0]->contact_person,
                        'customer_city' => $quotationDetail[0]->city,
                        'customer_state' => $quotationDetail[0]->state,
                        'customer_address' => $quotationDetail[0]->address,
                        'quotation_number' => $quotationDetail[0]->quotation_number,
                        'date' => ($quotationDetail[0]->date == '') ? null : dateFormat('d-m-Y', $quotationDetail[0]->date),
                        'total_amount' => $quotationDetail[0]->total_amount,
                        'remarks' => $quotationDetail[0]->remarks,
                        'status' => getStatusNameByCode($quotationDetail[0]->status),
                        'is_emailsent' => $quotationDetail[0]->is_emailsent ,
                    ];
                    $quotationItems = (new QuotationItem())->getQuotationItems(['quotation_id' => $id]);
                    if( count($quotationItems) > 0 ) {
                        $items = [];
                        foreach ($quotationItems as $item) {
                            $items[] = [
                                'id' => $item->id,
                                'description' => $item->description,
                                'price' => $item->price,
                            ];

                        }
                        $result['quotation_items'] = $items;
                    }

                    //dd($result,$items);

                    $pdf = \PDF::loadView('quotation.invoice_print', ['id' => $id, 'result' => $result, 'items' => $items,  'pdf' => 1] );
                    //dd($pdf);
                    return $pdf->stream();
                }
               else{
                    return apiResponse(false, 404, lang('messages.empty_result'));
                }
            }
            else {
                return apiResponse(false, 404, lang('messages.not_found', lang('quotation.quotation_details')));
            }
        }
        catch (Exception $exception) {
            return apiResponse(false, 500, lang('messages.server_error'));
        }


    }



    public function sendEmailQuotation($id)
    {
        $result = QuotationMaster::find($id);
        if (!$result) {
            return apiResponse(false, 404, lang('messages.empty_result'));
        }

        $customer = (new Customer)->find($result->customer_id);

        if ($customer->email == "") {
            return apiResponse(false, 404, lang('quotation.customer_email_not_found'));
        }
        //dd($result->toArray(), $customer, $customer->email);
        return (new QuotationMaster)->sendEmailToCustomer($id, $result, $customer, true);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateRevisedQuotationOLD()
    {
        $inputs = Input::all();
        $validator = ( new QuotationMaster)->validate( $inputs , null ,false , false);
        //$validator = (new SaleOrder)->validateSaleOrder($inputs, null, true, true);
        if ($validator->fails()) {
            return apiResponse(false, 406, errorMessages($validator->messages()));
        }

        try {
            \DB::beginTransaction();
            /* Check if the order exists */

            $QuotationId = $inputs['id'];
            $insertArray = [];
            $record = QuotationMaster::find($QuotationId);

            if (count($record) == 0) {
                return apiResponse(false, 406, 'Quotation order does not exists');
            }
            /* update the record */

            /*User Exists*/
            if($inputs['user_id'] == authUserId() || isAdmin()) {

                $userRecord = User::find($inputs['user_id']);
                if(count($userRecord) > 0){
                    $user_id = $inputs['user_id'];
                }
                else{
                    return apiResponse(false, 406, 'User Does Not Exists');
                }
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }

            /*Date Formatting*/
            $Date = dateFormat('Y-m-d H:i:s', $inputs['date'] . ' ' . date('H:i:s'));


            /*Customer Exists*/
            $customerRecord = Customer::find($inputs['customer_id']);
            if(count($customerRecord) > 0){
                $customer_id = $inputs['customer_id'];
            }
            else{
                return apiResponse(false, 406, 'Customer Does Not Exists');
            }

            /* add Revision Number */
            $old_quotation_number= $record['quotation_number'];
            //unset($inputs['quotation_number']);
            if($record['revised_id'] == null){
                $revised_tag = 'RV-01';
                $revised_quotation_number =  $old_quotation_number . '-' . $revised_tag;
            }
            else{
                $number = explode( '-' , $old_quotation_number);
                $number['3'] = revisionPaddingLeft(++$number['3']);
                $revised_quotation_number= implode("-",$number);
            }

            //dd($revised_quotation_number);

            $quotationUpdateArray = [
                //'revised_id'        =>  null ,
                //'customer_id'       =>  $customer_id,
                'user_id'             =>  $user_id,
                //'quotation_number'  =>  $inputs['quotation_number'],
                //'total_amount'      =>  $total_amount,
                //'date'              =>  convertToUtc($Date),
                //'remarks'           =>  $inputs['remarks'],
                'status'              =>  2,
            ];
            //dd($inputs,$quotationUpdateArray);
            (new QuotationMaster)->store($quotationUpdateArray, $QuotationId);
            $quotationRevisedArray= [
                'revised_id'          =>  $QuotationId ,
                'customer_id'         =>  $customer_id,
                'user_id'             =>  $user_id,
                'quotation_number'    =>  $revised_quotation_number,
                //'total_amount'      =>  $total_amount,
                'date'                =>  convertToUtc($Date),
                'remarks'             =>  $inputs['remarks'],
                'status'              =>  2,
                'is_emailsent'        =>  0,
            ];
            $revised_quotation_id = (new QuotationMaster)->store($quotationRevisedArray);


            if($revised_quotation_id)
            {
                $total = 0;
                $itemCounts= count($inputs['description']);
                for ($x = 0; $x < $itemCounts; $x++) {
                    $quotationItemArray  =
                        [
                            'quotation_id' =>  $revised_quotation_id,
                            'description' => $inputs['description'][$x],
                            'price' => $inputs['price'][$x],

                        ];
                    (new QuotationItem)->store($quotationItemArray , null, false);

                    $total += $inputs['price'][$x];
                }

                $update = [
                    'total_amount'      => $total
                ];
                (new QuotationMaster)->quotationOrderUpdate($update, $revised_quotation_id);


                \DB::commit();
                return apiResponse(true, 200, lang('quotation.revised'));
            }
            else
            {
                return apiResponse(false, 207, lang('messages.server_error'));
            }
        }

        catch
        (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }

    public function generateRevisedQuotation()
    {
        $inputs = Input::all();
        //dd($inputs);
        $validator = ( new QuotationMaster)->validate( $inputs , null ,false , false);
        //$validator = (new SaleOrder)->validateSaleOrder($inputs, null, true, true);
        if ($validator->fails()) {
            return apiResponse(false, 406, errorMessages($validator->messages()));
        }

        try {
            \DB::beginTransaction();
            /* Check if the order exists */

            $QuotationId = $inputs['id'];
            $insertArray = [];
            $record = QuotationMaster::find($QuotationId);
            if($record['is_active'] != 1){
                return apiResponse(false, 406, 'Please Check Quotation order revision exists');
            }
            //dd($record->toArray());

            if (count($record) == 0) {
                return apiResponse(false, 406, 'Quotation order does not exists');
            }
            /* update the record */

            /*User Exists*/
            if($inputs['user_id'] == authUserId() || isAdmin()) {

                $userRecord = User::find($inputs['user_id']);
                if(count($userRecord) > 0){
                    $user_id = $inputs['user_id'];
                }
                else{
                    return apiResponse(false, 406, 'User Does Not Exists');
                }
            }
            else {
                return apiResponse(false, 404, lang('auth.customer_not_accessible'));
            }

            /*Date Formatting*/
            $Date = dateFormat('Y-m-d H:i:s', $inputs['date'] . ' ' . date('H:i:s'));


            /*Customer Exists*/
            $customerRecord = Customer::find($inputs['customer_id']);
            if(count($customerRecord) > 0){
                $customer_id = $inputs['customer_id'];
            }
            else{
                return apiResponse(false, 406, 'Customer Does Not Exists');
            }

            /* add Revision Number */
            $old_revised_number= $record['revised_number'];
            //unset($inputs['quotation_number']);
            if($record['revised_id'] == null){

                $revised_number = 'RV-01';
            }
            else{

                $revised_number = revisionPaddingLeft(++$old_revised_number);

            }

            $quotationUpdateArray = [
                //'revised_id'        =>  null ,
                //'customer_id'       =>  $customer_id,
                'user_id'             =>  $user_id,
                //'revised_number'      =>  $revised_number,
                //'quotation_number'  =>  $inputs['quotation_number'],
                //'total_amount'      =>  $total_amount,
                //'date'              =>  convertToUtc($Date),
                //'remarks'           =>  $inputs['remarks'],
                'status'              =>  2,
                'is_active'           =>  0,
            ];
            //dd($inputs,$quotationUpdateArray);
            (new QuotationMaster)->store($quotationUpdateArray, $QuotationId);
            $quotationRevisedArray= [
                'revised_id'          =>  $QuotationId,
                'customer_id'         =>  $customer_id,
                'user_id'             =>  $user_id,
                'quotation_number'    =>  $record['quotation_number'],
                'revised_number'      =>  $revised_number,
                //'total_amount'      =>  $total_amount,
                'date'                =>  convertToUtc($Date),
                'remarks'             =>  isset($inputs['remarks'])?$inputs['remarks']:'',
                'status'              =>  2,
                'is_emailsent'        =>  0,
                'is_active'           =>  1,
            ];
            $revised_quotation_id = (new QuotationMaster)->store($quotationRevisedArray);


            if($revised_quotation_id)
            {
                $total = 0;
                $itemCounts= count($inputs['description']);
                for ($x = 0; $x < $itemCounts; $x++) {
                    $quotationItemArray  =
                        [
                            'quotation_id' =>  $revised_quotation_id,
                            'description' => $inputs['description'][$x],
                            'price' => $inputs['price'][$x],

                        ];
                    (new QuotationItem)->store($quotationItemArray , null, false);

                    $total += $inputs['price'][$x];
                }

                $update = [
                    'total_amount'      => $total
                ];
                (new QuotationMaster)->quotationOrderUpdate($update, $revised_quotation_id);


                \DB::commit();
                return apiResponse(true, 200, lang('quotation.revised'));
            }
            else
            {
                return apiResponse(false, 207, lang('messages.server_error'));
            }
        }

        catch
        (Exception $exception) {
            \DB::rollBack();
            return apiResponse(false, 500, lang('messages.server_error'));
        }
    }


}