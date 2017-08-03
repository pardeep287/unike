<?php

namespace App\Http\Controllers;

use App\Hsn;
use App\Product;
use App\ProductCost;
use App\ProductDimensions;
use App\ProductSizeDimensions;
use App\ProductSizeDimensionsValue;
use App\ProductSizes;
use App\ProductThumbImages;
use App\Size;
use App\Tax;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('product.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hsn = (new Hsn)->getHsnService();

        $size = (new Size)->getSizeService();
        $tax = (new Tax)->getTaxService();
        $dimension = getStatusNameByCode(false,false,true);
        /*$dimension= [
          '' => "-Dimension-",
          'A'=> "A",
          'B' => "B",
          'C' => "C",
          'D' => "D",
          'E' => "E",
          'F' => "F",
          'G' => "G",
        ];*/
        //dd($dimension);
        return view('product.create', compact('hsn', 'size' , 'dimension' ,'tax') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $inputs = \Input::all();
        //dd($inputs);


        $validator = (new Product)->validateProduct($inputs);
        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());
        }

        if(count(array_unique($inputs['size_master_id'])) < count($inputs['size_master_id']))
        {
            return validationResponse(true, 207, "Same Size Not allowed,Please Select the Unique Size");
        }
        if(count(array_unique($inputs['dimension_id'])) < count($inputs['dimension_id']))
        {
            return validationResponse(true, 207, "Error:!Same Dimension Not allowed,Please Select the Unique Dimension");
        }
       // dd($inputs);
        try {
            \DB::beginTransaction();


            $product_image = \Input::file('product_image');
            if($product_image){
                $fileName = str_random(6) . '_' . str_replace(' ', '_', $product_image->getClientOriginalName());
            }

            $product_dim_image = \Input::file('product_dim_image');
            if($product_dim_image){
                $dimImageFileName = str_random(6) . '_' . str_replace(' ', '_', $product_dim_image->getClientOriginalName());
            }
            //dd($fileName,$inputs['price'],$inputs['description'],$inputs);
            $productData = [
                'company_id'    => loggedInCompanyId(),
                'hsn_id'        => $inputs['hsn_id'],
                'tax_id'        => $inputs['tax_id'],
                'name'          => $inputs['name'],
                'code'          => isset($inputs['code'])?$inputs['code']:null,
                'description'   => isset($inputs['description'])?$inputs['description']:null,
                'p_image'       => isset($fileName)?$fileName:null,
                'd_image'       => isset($dimImageFileName)?$dimImageFileName:null,
                'status'        => $inputs['status'],
                'created_by'    => authUserId(),
            ];
            $product_id = (new Product)->store($productData);
                //move image to folder
                if($product_image){
                    $dirname = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$product_id;
                    if (!file_exists($dirname)) {
                        mkdir($dirname, 0777);
                        $product_image->move($dirname, $fileName);
                    } else {
                        $product_image->move($dirname, $fileName);
                    }
                }
                if($product_dim_image){
                    $dirname2 = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$product_id;
                    if (!file_exists($dirname2)) {
                        mkdir($dirname2, 0777);
                        $product_dim_image->move($dirname2, $dimImageFileName);
                    } else {
                        $product_dim_image->move($dirname2, $dimImageFileName);
                    }
                }
            if($product_id) {
                if(count($inputs['dimension_id']) > 0 && $inputs['dimension_id'][0] != null) {
                    foreach ($inputs['dimension_id'] as $dimkey => $dimId) {
                        if ($dimId != "" && $inputs['dimension_id'][$dimkey] != "") {
                            $productDimensionData[] = [
                                 'product_id'       => $product_id,
                                 'dimension_name'   => $inputs['dimension_id'][$dimkey],
                                //'dimensions_size' => 0,
                            ];
                        }
                    }
                    (new ProductDimensions)->store($productDimensionData,null, true);
                }
            }

            /* check if size has values */
            if(count($inputs['size_master_id']) > 0 && $inputs['size_master_id'][0] != null) {
               /* $productDimensionDataNew = '';
                if(count($inputs['dimension_id']) > 0 && $inputs['dimension_id'][0] != null) {
                    foreach ($inputs['dimension_id'] as $dimkey => $dimId) {
                        if ($dimId != "" && $inputs['dimension_id'][$dimkey] != "") {
                            $productDimensionData[] = $inputs['dimension_id'][$dimkey];

                        }
                    }
                    $productDimensionDataNew=implode(",", $productDimensionData);

                }*/
                foreach ($inputs['size_master_id'] as $key => $sizeId) {
                    if ($sizeId != "" && $inputs['size_master_id'][$key] != "") {
                       $productSizesArray = [
                            'size_master_id'    => $inputs['size_master_id'][$key],
                            'product_id'        => $product_id,
                            //'dimension_name'    => $productDimensionDataNew,
                            'status'            => 1,
                            'created_by'        => authUserId(),
                        ];
                    }
                    //dd($productSizesArray);
                    $productSizes_id = (new ProductSizes)->store($productSizesArray);
                    if(count($inputs['price']) > 0 && $inputs['price'][$key] != null) {
                        $priceData = [
                            'product_id'    => $product_id,
                            'size_id'       => $productSizes_id,
                            'price'         => $inputs['price'][$key],
                            //'price'         => $inputs['price'][$keyprice],
                            'manual_price'  => 0,
                            'discount'      => 0,
                            'wef'           => convertToUtc(),
                            //'wet'         => 0,
                            'status'        => 1,
                        ];
                        (new ProductCost)->store($priceData, null , true);
                    }
                } // for loop of Size array
            }
            else{
                return validationResponse(true, 207, "Please Select the Normal Size for Product");
            }
            \DB::commit();
            if (isset($inputs['save_edit'])) {
                $route = route('product.edit', ['id' => $product_id, 'tab' => 1]);
                $lang = lang('messages.updated', lang('products.product'));
                return validationResponse(true, 201, $lang, $route);
                //return redirect()->route('product.edit', ['id' => $product_id, 'tab' => 1]);
            }
            /*return redirect()->route('hsn.index')
                ->with('success', lang('messages.created', lang('hsn.hsn')));*/
            $route = route('product.index');
            $lang = lang('messages.updated', lang('products.product'));
            return validationResponse(true, 201, $lang, $route);
        } catch (\Exception $exception) {

            \DB::rollBack();
            /*return redirect()->route('hsn.create')
                ->withInput()
                ->with('error', $exception->getMessage() . lang('messages.server_error'));*/
            return validationResponse(false, 207, $exception->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id = null)
    {
        $product = Product::find($id);
        if (!$product) {
            abort(404);
        }
        $hsn = (new Hsn)->getHsnService();
        $size = (new Size)->getSizeService();
        $tax = (new Tax)->getTaxService();
        $tab = \Input::get('tab', 1);
        //$tab = 2;
        $dimension_name=(new ProductDimensions())->getProductDimension($id);
        $thumbImages=(new ProductThumbImages())->getProductThumbImages($id);
        //dd($thumbImages);
        $dimension_value=(new ProductSizeDimensionsValue)->getProductDimensionValue($id);

        $SizePrice = (new ProductSizes)->getPriceListProductSize($id);
        //dd($SizePrice->toArray());
        //dd($dimension_name->toArray(),$dimension_value->toArray(),$SizePrice->toArray());

        //Show only Unselected Sizes
        $NotSelectedSize = [];
        $array2 = [];
        foreach($SizePrice->toArray() as $key=>$sp) {
            $array2[]= $sp['normal_size'];
        }
        if(count($array2) > 0) {
            $NotSelectedSize = array_diff($size, $array2);
        }

        return view('product.edit', compact('product', 'id', 'hsn', 'tax' ,'tab','SizePrice','NotSelectedSize','dimension_name','dimension_value', 'thumbImages'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function productImageDelete($id)
    {
        if (!\Request::ajax()) {
            return lang('messages.server_error');
        }

        $imageData= (new ProductThumbImages)->find($id);
        //dd($imageData);
        if(!$imageData){
            return lang('messages.thumb_error');
        }

        try {
             (new ProductThumbImages())->drop($id);
             $response = ['status' => 1, 'message' => lang('messages.deleted', lang('products.thumb_image'))];
             $folder = ROOT . \Config::get('constants.UPLOADS-PRODUCT') . $imageData->product_id . '/';
             if (file_exists($folder . $imageData->image_name)) {
                    unlink($folder . $imageData->image_name);
             }


        } catch (\Exception $exception) {
            $response = ['status' => 0, 'message' => lang('messages.server_error')];
        }
        return json_encode($response);
    }

    public function update($id)
    {
        $product = Product::find($id);
        $inputs = \Input::all();
        //dd($inputs);
        $tab = \Input::get('tab', 1);
        //$tab = 1;
        if (!$product) {
            $route = route('product.edit', ['id' => $id]);
            $lang = lang('messages.invalid_id', string_manip(lang('products.product')));
            return validationResponse(true, 201, $lang, $route);
            //return redirect()->route('product.edit', ['id' => $id])
            //->with('error', lang('messages.invalid_id', string_manip(lang('products.product'))));
        }


        $validator = (new Product())->validateProduct($inputs,$id);
        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());

        }

        try {
            \DB::beginTransaction();
            if(isset($inputs['product_image']) && $inputs['product_image'] != null)
            {

                $oldProductLogo = $product->p_image;
                $product_image = \Input::file('product_image');
                $fileName= '';
                if($product_image){
                    $fileName = str_random(6) . '_' . str_replace(' ', '_', $product_image->getClientOriginalName());
                }

                //$fileName = str_random(6) . '_' . str_replace(' ', '_', $productImage->getClientOriginalName());
                $folder = ROOT . \Config::get('constants.UPLOADS-PRODUCT') . $product->id . '/';

                $dirname = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$product->id;
                if (!file_exists($dirname)) {
                    mkdir($dirname, 0777);
                }
                if($product_image){
                    if ($product_image->move($folder, $fileName)) {
                        if (!empty($oldProductLogo) && file_exists($folder . $oldProductLogo)) {
                            unlink($folder . $oldProductLogo);
                        }
                    }
                }

                unset($inputs['product_image']);
                $inputs = $inputs + [
                        'p_image' => $fileName,
                    ];
            }
            if(isset($inputs['product_dim_image']) && $inputs['product_dim_image'] != null)
            {

                $oldDimImage = $product->d_image;
                $product_dim_image = \Input::file('product_dim_image');
                $dimImageFileName = '';
                if($product_dim_image){
                    $dimImageFileName = str_random(6) . '_' . str_replace(' ', '_', $product_dim_image->getClientOriginalName());
                }
                //$fileName = str_random(6) . '_' . str_replace(' ', '_', $productImage->getClientOriginalName());
                $folder = ROOT . \Config::get('constants.UPLOADS-PRODUCT') . $product->id . '/';

                $dirname = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$product->id;
                if (!file_exists($dirname)) {
                    mkdir($dirname, 0777);
                }

                if($product_dim_image){

                    if ($product_dim_image->move($folder, $dimImageFileName)) {
                        if (!empty($oldDimImage) && file_exists($folder . $oldDimImage)) {
                            unlink($folder . $oldDimImage);
                        }
                    }
                }
                unset($inputs['product_dim_image']);
                $inputs = $inputs + [
                        'd_image' => $dimImageFileName,
                    ];
            }

            if(isset($inputs['thumb_image']) && count($inputs['thumb_image']) >0 && $inputs['thumb_image'][0] != null )
            {
                $thumbImagesArray = [];
                $thumbImagesArray = \Input::file('thumb_image');
                foreach ($thumbImagesArray as $key=>$data) {

                    $thumbImageName = str_random(6) . '_' . str_replace(' ', '_', $data->getClientOriginalName());

                    $folder = ROOT . \Config::get('constants.UPLOADS-PRODUCT') . $product->id . '/';
                    $dirname = ROOT . \Config::get('constants.UPLOADS-PRODUCT').$product->id;
                    if (!file_exists($dirname)) {
                        mkdir($dirname, 0777);
                    }
                    $isMove=$data->move($folder, $thumbImageName);

                    if($isMove) {
                        $thumbImagesArrayData[] = [
                            'product_id' => $id,
                            'image_name' => $thumbImageName,
                        ];
                    }
                }
                unset($inputs['thumb_image']);
                (new ProductThumbImages())->store($thumbImagesArrayData,null,true);

            }
            if(!array_key_exists('updated_by', $inputs)) {
                $inputs = $inputs + [
                        'updated_by' => authUser()->id,
                    ];
            }

            (new Product)->store($inputs, $id);
            \DB::commit();
            $route = route('product.edit', ['id'=>$id,'tab' => $tab]);
            $lang = lang('messages.updated', lang('products.product'));
            return validationResponse(true, 201, $lang, $route);
            /*return redirect()->route('product.edit', ['id'=>$id,'tab' => $tab])
                ->with(['success' => lang('messages.updated', lang('products.product'))]);*/

        } catch (Exception $e) {
            \DB::rollback();
            /*return redirect()->back()
                ->withInput($inputs)
                ->with('error', lang('messages.server_error'));*/
            return validationResponse(false, 207, lang('messages.server_error'));
        }
    }

    /**
     * Used to update company active status.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function storeSize()
    {
        $inputs = \Input::all();
        $tab = \Input::get('tab', 2);
        $validator = (new Product)->validateProductSize($inputs);
        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());        }

        try {
            \DB::beginTransaction();
            $product_id = $inputs['product_id'];



            $dim_name=(new ProductSizes)->findByProductID($product_id)['dimension_name'];
            //dd($inputs,$dim_name);
            /* check if size has values */
            if (isset($inputs['size_master_id']) && $inputs['size_master_id'] != "") {
                $productSizesArray = [
                    'size_master_id'=> $inputs['size_master_id'],
                    'product_id'    => $product_id,
                    'dimension_name'=> $dim_name,
                    'status'        => 1,
                    'created_by'    => authUserId(),
                ];
                //dd($productSizesArray);
                $productSizes_id = (new ProductSizes)->store($productSizesArray);
                if($productSizes_id){
                    if (isset($inputs['price']) && $inputs['price'] != "") {
                        $priceData = [
                            'product_id'    => $product_id,
                            'size_id'       => $productSizes_id,
                            'price'         => $inputs['price'],
                            //'price'         => $inputs['price'][$keyprice],
                            'manual_price'  => 0,
                            'discount'      => 0,
                            'wef'           => convertToUtc(),
                            //'wet'         => 0,
                            'status'        => 1,
                        ];

                        (new ProductCost)->store($priceData, null , true);
                    }
                }
            }
            else{
                return validationResponse(true, 207, "Please Select the Normal Size for Product");
            }

            \DB::commit();
            /*return redirect()->route('hsn.index')
                ->with('success', lang('messages.created', lang('hsn.hsn')));*/
            $route = route('product.edit',  ['id'=>$product_id,'tab' => $tab]);
            $lang = lang('messages.updated', lang('products.product'));
            return validationResponse(true, 201, $lang, $route);
        } catch (\Exception $exception) {

            \DB::rollBack();
            /*return redirect()->route('hsn.create')
                ->withInput()
                ->with('error', $exception->getMessage() . lang('messages.server_error'));*/
            return validationResponse(false, 207, $exception->getMessage());
        }
    }

    public function storeDimensionValue()
    {
        $inputs = \Input::all();
        //dd($inputs);
        /*$validator = (new Product)->validateProductSizeDim($inputs);
        if ($validator->fails()) {
            return validationResponse(false, 206, "", "", $validator->messages());
        }*/

        $tab = \Input::get('tab', 2);
        //$tab = 2;
        //dd($tab);
        try {
            \DB::beginTransaction();
            $product_id = $inputs['product_id'];

            if($product_id) {
                //change price code
                if(isset($inputs['change_price']) && $inputs['change_price']==1 )
                {
                   
                    $validator = (new ProductCost())->validatePrice($inputs);
                    if ($validator->fails()) {
                        return validationResponse(false, 206, "", "", $validator->messages());
                       /* return redirect()->route('product.edit',  ['id'=>$product_id,'tab' => $tab])
                            ->withInput()
                            ->withErrors($validator);*/
                    }

                    
                    if(isset($inputs['size_id']))
                    {
                        $currentId = (new ProductCost())->findBySizeID($inputs['size_id'])['id'];
                        if($currentId) {

                            $priceDataOld = [
                                'status' => 0,
                                'wet' => convertToUtc(),
                            ];
                            //dd($priceDataOld, $currentId);
                            $updatedRow = (new ProductCost())->store($priceDataOld, $currentId, false);
                            if ($updatedRow) {

                                $priceData = [
                                    'product_id' => $product_id,
                                    'size_id' => $inputs['size_id'],
                                    'price' => $inputs['price'],
                                    //'price'         => $inputs['price'][$keyprice],
                                    'manual_price' => 0,
                                    'discount' => 0,
                                    'wef' => convertToUtc(),
                                    //'wet'         => 0,
                                    'status' => 1,
                                ];

                                (new ProductCost())->store($priceData);
                            }
                        }
                    }
                    else{
                            return validationResponse(true, 207, "Some Error Occurred ,Please Try Again!");
                        /*return redirect()->route('product.edit',  ['id'=>$product_id,'tab' => $tab])
                            ->with('error', lang('messages.invalid_id', string_manip(lang('products.product'))));*/
                    }

                }
                else {
                        if (isset($inputs['dim']) && count($inputs['dim']) > 0) {

                        /*foreach ($inputs['dim'] as $productSizeId => $Value) {
                            foreach ($Value as $dimensionSizeId => $DimensionValue) {
                                if ($DimensionValue != "" || $DimensionValue==null) {
                                    return redirect()->route('product.edit', ['id'=>$product_id,'tab' => $tab])
                                        ->withInput($inputs)
                                        ->with('error', lang('messages.server_error'));
                                }
                            }
                        }*/
                        //dd($inputs);
                            foreach ($inputs['dim'] as $productSizeId => $Value) {
                                $status = ProductSizes::find($productSizeId)['status'];
                                if ($status==0) {
                                    //return validationResponse(false, 206, "", "", $validator->messages());
                                    return redirect()->route('product.edit',  ['id'=>$product_id,'tab' => $tab])
                                        ->withInput()
                                        ->with('error', lang('messages.invalid_post_data', string_manip(lang('products.product'))));
                                }
                            }

                        foreach($inputs['dim'] as $productSizeId=>$Value) {

                            $DimensionData = [];
                            //$productDimensionValueNew = [];
                            foreach ($Value as $dimensionSizeId => $DimensionValue) {
                                /*if ($value != "") {
                                    $DimensionValue[] = $value;
                                }*/
                                $DimensionData[] = [
                                    'product_id'     => $product_id,
                                    'size_id'        => $productSizeId,
                                    'dimension_id'   => $dimensionSizeId,
                                    'dimension_value'=> $DimensionValue,

                                ];

                            }
                            //dd($inputs,$DimensionData);
                            (new ProductSizeDimensionsValue)->store($DimensionData,null,true);
                            /*if ($DimensionValue) {
                                $productDimensionValueNew = implode(",", $DimensionValue);
                                $UpdateData = [
                                    'dimension_value' => $productDimensionValueNew,
                                ];
                                (new ProductSizes)->store($UpdateData, $productSizeId);
                            }*/

                        } //dim loop end here

                    }
                    else{
                        //dim_id
                        if (isset($inputs['dim_id']) && count($inputs['dim_id']) > 0) {
                                //dd($inputs);
                            /*foreach ($inputs['dim'] as $productSizeId => $Value) {
                                foreach ($Value as $dimensionSizeId => $DimensionValue) {
                                    if ($DimensionValue != "" || $DimensionValue==null) {
                                        return redirect()->route('product.edit', ['id'=>$product_id,'tab' => $tab])
                                            ->withInput($inputs)
                                            ->with('error', lang('messages.server_error'));
                                    }
                                }
                            }*/
                            //dd($inputs);
                            foreach($inputs['dim_id'] as $productSizeId=>$Value) {

                                $DimensionData = [];
                                //$productDimensionValueNew = [];
                                foreach ($Value as $dimensionSizeValueId => $DimensionValue) {
                                    /*if ($value != "") {
                                        $DimensionValue[] = $value;
                                    }*/
                                    $DimensionData = [
                                        //'product_id'     => $product_id,
                                        //'size_id'        => $productSizeId,
                                        //'dimension_id'   => $dimensionSizeId,
                                        'dimension_value'=> $DimensionValue,

                                    ];
                                    (new ProductSizeDimensionsValue)->store($DimensionData,$dimensionSizeValueId,false);

                                }
                                //dd($inputs,$DimensionData);

                                /*if ($DimensionValue) {
                                    $productDimensionValueNew = implode(",", $DimensionValue);
                                    $UpdateData = [
                                        'dimension_value' => $productDimensionValueNew,
                                    ];
                                    (new ProductSizes)->store($UpdateData, $productSizeId);
                                }*/

                            } //dim loop end here

                        }
                    }//else ends

                }
            }
            \DB::commit();

            if(isset($inputs['size_id'])) {
                $route = route('product.edit',  ['id'=>$product_id,'tab' => $tab]);
                $lang = lang('messages.updated', lang('products.product'));
                return validationResponse(true, 201, $lang, $route);
                /*return redirect()->route('product.edit', ['id'=>$product_id,'tab' => $tab])
                    ->with(['success' => lang('messages.updated', lang('products.price'))]);*/
            }

            $route = route('product.edit',  ['id'=>$product_id,'tab' => $tab]);
            $lang = lang('messages.updated', lang('products.product'));
            return validationResponse(true, 201, $lang, $route);
            /*return redirect()->route('product.edit', ['id'=>$product_id,'tab' => $tab])
                ->with(['success' => lang('messages.updated', lang('products.dim'))]);*/
        } catch (\Exception $exception) {

            \DB::rollBack();
            $route = route('product.edit',  ['id'=>$product_id,'tab' => $tab]);
            $lang = lang('messages.updated', lang('products.product'));
            return validationResponse(true, 201, $lang, $route);
             //return validationResponse(false, 207, $exception->getMessage());
            /*return redirect()->route('product.edit', ['id'=>$product_id,'tab' => $tab])
                ->withInput($inputs)
                //->with('error', lang('messages.server_error'));
            ->with('error',  $exception->getFile().$exception->getMessage().$exception->getLine(). lang('messages.server_error'));*/
        }
    }

    public function productSizeToggle($id)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }
        $tab = \Input::get('tab', 2);


        try {
            $size = ProductSizes::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('products.product')));
        }

        $size->update(['status' => !$size->status]);
        $response = ['status' => 1, 'data' => (int)$size->status . '.gif'];
        // return json response
        return json_encode($response);
    }

    public function productToggle($id)
    {
        if (!\Request::isMethod('post') && !\Request::ajax()) {
            return lang('messages.server_error');
        }


        try {
            $product = Product::find($id);
        } catch (\Exception $exception) {
            return lang('messages.invalid_id', string_manip(lang('products.product')));
        }

        $product->update(['status' => !$product->status]);
        $response = ['status' => 1, 'data' => (int)$product->status . '.gif'];
        // return json response
        return json_encode($response);
    }

    /**
     * Used to load more records and render to view.
     *
     * @param int $pageNumber
     * @return \Illuminate\Http\Response
     */
    public function productPaginate(Request $request, $pageNumber = null)
    {

        if (!\Request::isMethod('post') && !\Request::ajax()) {

            return lang('messages.server_error');
        }

        try {


            $inputs = $request->all();

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
            if (isset($inputs['form-search']) && $inputs['form-search'] != '') {
                $inputs = array_filter($inputs);
                unset($inputs['_token']);

                $data = (new Product)->getProduct($inputs, $start, $perPage);
                $total = (new Product)->totalProduct($inputs);
                $total = $total->total;
            } else {
                $data = (new Product)->getProduct($inputs, $start, $perPage);
                $total = (new Product)->totalProduct($inputs);
                $total = $total->total;
            }


            return view('product.load_data', compact('data', 'total', 'page', 'perPage', 'inputs'));
        }
        catch (\Exception $exception) {

            echo 'Error'. $exception->getMessage();
        }
    }

    /**
     * Method is used to update status of group enable/disable
     *
     * @return \Illuminate\Http\Response
     */
    public function productAction()
    {
        $inputs = \Input::all();

        if (!isset($inputs['tick']) || count($inputs['tick']) < 1) {
            return redirect()->route('hsn.index')
                ->with('error', lang('messages.atleast_one', string_manip(lang('hsn.hsn'))));
        }

        $ids = '';
        foreach ($inputs['tick'] as $key => $value) {
            $ids .= $value . ',';
        }

        $ids = rtrim($ids, ',');
        $status = 0;
        if (isset($inputs['active'])) {
            $status = 1;
        }

        Company::whereRaw('id IN (' . $ids . ')')->update(['status' => $status]);
        return redirect()->route('hsn.index')
            ->with('success', lang('messages.updated', lang('hsn.hsn_status')));
    }

}
