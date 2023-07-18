<?php

namespace Modules\Sale\Http\Controllers;

use Milon\Barcode\DNS1D;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Sale\Entities\Sale;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\SaleTax;
use Modules\Sale\Entities\SaleUnit;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Sale\Entities\SaleBrand;
use Modules\Sale\Entities\SaleReturn;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleVariant;
use Modules\Sale\Entities\SaleCategory;
use Modules\Sale\Entities\SalePurchase;
use Modules\Sale\Entities\SaleWarehouse;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleProductVariant;
use Modules\Sale\Entities\SaleReturnPurchase;
use Modules\Sale\Entities\SaleProductWarehouse;

class SaleProductController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
            $data['title'] = _trans('sale.Product List');
            $data['search'] = '';
            $data['products'] = SaleProduct::with('brand','category','unit');
            if ($search != "") {
                $data['products'] = $data['products']->where('name', 'like', '%' . $search . '%');
            }
    
            $data['products'] = $data['products']->where('is_active', true)->latest()->paginate($entries);

            return view('sale::product.product.index',compact( 'data'));
       
    }
    public function create()
    {
        $data['title'] =  _trans('sale.Product Create');
        $ot_crm_product_list_without_variant = $this->productWithoutVariant();
        $ot_crm_product_list_with_variant = $this->productWithVariant();
        $ot_crm_brand_list = SaleBrand::where('is_active', true)->get();
        $ot_crm_category_list = SaleCategory::where('is_active', true)->get();
        $ot_crm_unit_list = SaleUnit::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        return view('sale::product.product.create',compact('data','ot_crm_product_list_without_variant', 'ot_crm_product_list_with_variant', 'ot_crm_brand_list', 'ot_crm_category_list', 'ot_crm_unit_list', 'ot_crm_tax_list', 'ot_crm_warehouse_list'));
        
    }

    public function productWithVariant()
    {
        return SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
                ->ActiveStandard()
                ->whereNotNull('is_variant')
                ->select('sale_products.id', 'sale_products.name', 'sale_product_variants.item_code')
                ->orderBy('position')->get();
    }

    public function productWithoutVariant()
    {
        return SaleProduct::ActiveStandard()->select('id', 'name', 'code')
                ->whereNull('is_variant')->get();
    }
    

    public function saleUnit($id)
    {
        $unit = SaleUnit::where("base_unit", $id)->orWhere('id', $id)->pluck('unit_name','id');
        return json_encode($unit);
    }

    public function generateCode()
    {
        $code = random_int(100000, 999999);
        return $code;
    }

    // productDetails 
    public function productDetails($id)
    {
        $product = SaleProduct::find($id);
        $data['product'] = [
            
            'id' => $product->id,
            'code' => $product->code,
            'name' => $product->name,
            'image' => asset('public/images/product') . '/' . $product->image,
            'cost' => $product->cost,
            'price' => $product->price,
            'qty' => $product->qty,
            'alert_quantity' => $product->alert_quantity,
            'tax' => $product->tax->name??'N/A',
            'tax_method' => $product->tax_method == 1 ? 'Exclusive' : 'Inclusive',
            'unit' => $product->unit->unit_name,
            'brand' => $product->brand->title,
            'category' => $product->category->name,
            'product_details' => $product->product_details,
            'type' => $product->type,
        ];

        $warehouses = SaleProductWarehouse::with('productVariant')->where('product_id', $id)
            ->with('warehouse')
            ->get(['id', 'qty', 'product_batch_id', 'imei_number', 'warehouse_id']);

        $data['warehouses'] = $warehouses->map(function ($war) {
            return [
                'id' => $war['id'],
                'name' => $war->warehouse['name'],
                'qty' => $war['qty'],
                'batch_no' => $war['product_batch_id'] ?? 'N/A',
                'imei_number' => $war['imei_number'] ?? 'N/A',
            ];
        });


        $variants = SaleProductVariant::with('variant')->where('product_id', $id)->get();
        $data['variants'] = $variants->map(function ($var) {
            return [
                'id' => $var['id'],
                'variant' => $var->variant->name,
                'item_code' => $var['item_code'],
                'additional_cost' => $var['additional_cost'],
                'additional_price' => $var['additional_price'],
                'qty' => $var['qty'],
            ];
        });

        $data['warehouse_variants'] = $warehouses->map(function ($war) {
            return [
                'id' => $war['id'],
                'name' => $war->warehouse['name'],
                'variant' => $war->productVariant->variant['name'] ?? 'N/A',
                'qty' => $war->productVariant->qty ?? 0,
            ];
        });


        return json_encode($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:sale_products,code',
            'category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required',
            ],
            [
                'name.required' => 'Name field is required!',
                'code.required' => 'Code field is required and must be unique!',
                'category_id.required' => 'Categoey field is required!',
                'brand_id.required' => 'Brand field is required!',
                'price.required' => 'Price field is required!'
            ] 
        );


        $data = $request->except('image', 'file');
        
        if(isset($data['is_variant'])) {
            $data['variant_option'] = json_encode($data['variant_option']);
            $data['variant_value'] = json_encode($data['variant_value']);
        }
        else {
            $data['variant_option'] = $data['variant_value'] = null;
        }
        $data['name'] = preg_replace('/[\n\r]/', "<br>", htmlspecialchars(trim($data['name'])));
        if($data['type'] == 'combo') {
            $data['product_list'] = implode(",", $data['product_id']);
            $data['variant_list'] = implode(",", $data['variant_id']);
            $data['qty_list'] = implode(",", $data['product_qty']);
            $data['price_list'] = implode(",", $data['unit_price']);
            $data['cost'] = $data['unit_id'] = $data['purchase_unit_id'] = $data['sale_unit_id'] = 0;
        }
        elseif($data['type'] == 'digital' || $data['type'] == 'service')
            $data['cost'] = $data['unit_id'] = $data['purchase_unit_id'] = $data['sale_unit_id'] = 0;

        $data['product_details'] = str_replace('"', '@', $data['product_details']);

        if($data['starting_date'])
            $data['starting_date'] = date('Y-m-d', strtotime($data['starting_date']));
        if($data['last_date'])
            $data['last_date'] = date('Y-m-d', strtotime($data['last_date']));
        $data['is_active'] = true;
        $images = $request->image;
        $image_names = [];
        if($images) {          
            foreach ($images as $key => $image) {
                $imageName = $image->getClientOriginalName();
                $image->move('public/images/product', $imageName);
                $image_names[] = $imageName;
            }
            $data['image'] = implode(",", $image_names);
        }
        else {
            $data['image'] = 'blank_small.png';
        }
        $data['slug'] = Str::slug($data['name']);
        $file = $request->file;
        if ($file) {
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $fileName = strtotime(date('Y-m-d H:i:s'));
            $fileName = $fileName . '.' . $ext;
            $file->move('public/product/files', $fileName);
            $data['file'] = $fileName;
        }
        //return $data;
        $ot_crm_product_data = SaleProduct::create($data);
        //dealing with product variant
        if(!isset($data['is_batch']))
            $data['is_batch'] = null;
        if(isset($data['is_variant'])) {
            foreach ($data['variant_name'] as $key => $variant_name) {

                $ot_crm_variant_data = SaleVariant::firstOrCreate(['name' => $data['variant_name'][$key]]);
                $ot_crm_variant_data->name = $data['variant_name'][$key];
                $ot_crm_variant_data->save();
                $ot_crm_product_variant_data = new SaleProductVariant;             
                $ot_crm_product_variant_data->product_id = $ot_crm_product_data->id;
                $ot_crm_product_variant_data->variant_id = $ot_crm_variant_data->id;
                $ot_crm_product_variant_data->position = $key + 1;
                $ot_crm_product_variant_data->item_code = $data['item_code'][$key];
                $ot_crm_product_variant_data->additional_cost = $data['additional_cost'][$key];
                $ot_crm_product_variant_data->additional_price = $data['additional_price'][$key];
                $ot_crm_product_variant_data->qty = 0;
                $ot_crm_product_variant_data->save();
            }
        }
        if(isset($data['is_diffPrice'])) {
            foreach ($data['diff_price'] as $key => $diff_price) {
                if($diff_price) {
                    SaleProductWarehouse::create([
                        "product_id" => $ot_crm_product_data->id,
                        "warehouse_id" => $data["warehouse_id"][$key],
                        "qty" => 0,
                        "price" => $diff_price
                    ]);
                }
            }
        }
        Toastr::success(_trans('response.Product created successfully'), 'Success');
        return response()->json(['status' => 'success']);

    }


    public function edit($id)
    {
            $ot_crm_product_list_without_variant = $this->productWithoutVariant();
            $ot_crm_product_list_with_variant = $this->productWithVariant();
            $ot_crm_brand_list = SaleBrand::where('is_active', true)->get();
            $ot_crm_category_list = SaleCategory::where('is_active', true)->get();
            $ot_crm_unit_list = SaleUnit::where('is_active', true)->get();
            $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
            $ot_crm_product_data = SaleProduct::where('id', $id)->first();
            if($ot_crm_product_data->variant_option) {
                $ot_crm_product_data->variant_option = json_decode($ot_crm_product_data->variant_option);
                $ot_crm_product_data->variant_value = json_decode($ot_crm_product_data->variant_value);
            }
            $ot_crm_product_variant_data = $ot_crm_product_data->variant()->orderBy('position')->get();
            $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
            $noOfVariantValue = 0;
            $data['title'] =  _trans('sale.Product Edit');
            return view('sale::product.product.edit',compact('data','ot_crm_product_list_without_variant', 'ot_crm_product_list_with_variant', 'ot_crm_brand_list', 'ot_crm_category_list', 'ot_crm_unit_list', 'ot_crm_tax_list', 'ot_crm_product_data', 'ot_crm_product_variant_data', 'ot_crm_warehouse_list', 'noOfVariantValue'));
        
    }

    public function importProduct(Request $request)
    {   
        //get file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('message', 'Please upload a CSV file');

        $filePath=$upload->getRealPath();
        //open and read
        $file=fopen($filePath, 'r');
        $header= fgetcsv($file);
        $escapedHeader=[];
        //validate
        foreach ($header as $key => $value) {
            $lheader=strtolower($value);
            $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through other columns
        while($columns=fgetcsv($file))
        {
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }
           $data= array_combine($escapedHeader, $columns);
           
           if($data['brand'] != 'N/A' && $data['brand'] != ''){
                $ot_crm_brand_data = SaleBrand::firstOrCreate(['title' => $data['brand'], 'is_active' => true]);
                $brand_id = $ot_crm_brand_data->id;
           }
            else
                $brand_id = null;

           $ot_crm_category_data = SaleCategory::firstOrCreate(['name' => $data['category'], 'is_active' => true]);

           $ot_crm_unit_data = SaleUnit::where('unit_code', $data['unitcode'])->first();
           if(!$ot_crm_unit_data)
                return redirect()->back()->with('not_permitted', 'Unit code does not exist in the database.');

           $product = SaleProduct::firstOrNew([ 'name'=>$data['name'], 'is_active'=>true ]);
            if($data['image'])
                $product->image = $data['image'];
            else
                $product->image = 'blank_small.png';

           $product->name = htmlspecialchars(trim($data['name']));
           $product->code = $data['code'];
           $product->type = strtolower($data['type']);
           $product->barcode_symbology = 'C128';
           $product->brand_id = $brand_id;
           $product->category_id = $ot_crm_category_data->id;
           $product->unit_id = $ot_crm_unit_data->id;
           $product->purchase_unit_id = $ot_crm_unit_data->id;
           $product->sale_unit_id = $ot_crm_unit_data->id;
           $product->cost = str_replace(",","",$data['cost']);
           $product->price = str_replace(",","",$data['price']);
           $product->tax_method = 1;
           $product->qty = 0;
           $product->product_details = $data['productdetails'];
           $product->is_active = true;
           $product->save();

            if($data['variantname']) {
                //dealing with variants
                $variant_names = explode(",", $data['variantname']);
                $item_codes = explode(",", $data['itemcode']);
                $additional_prices = explode(",", $data['additionalprice']);
                foreach ($variant_names as $key => $variant_name) {
                    $variant = SaleVariant::firstOrCreate(['name' => $variant_name]);
                    if($data['itemcode'])
                        $item_code = $item_codes[$key];
                    else
                        $item_code = $variant_name . '-' . $data['code'];
                    
                    if($data['additionalprice'])
                        $additional_price = $additional_prices[$key];
                    else
                        $additional_price = 0;

                    SaleProductVariant::create([
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                        'position' => $key + 1,
                        'item_code' => $item_code,
                        'additional_price' => $additional_price,
                        'qty' => 0
                    ]);
                }
                $product->is_variant = true;
                $product->save();
            }
         }
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleProduct.index');

    }


    public function update(Request $request)
    {
            
            $ot_crm_product_data = SaleProduct::findOrFail($request->input('id'));
            $data = $request->except('image', 'file', 'prev_img');
            $data['name'] = htmlspecialchars(trim($data['name']));
            $data['slug'] = Str::slug($data['name']);

            if($data['type'] == 'combo') {
                $data['product_list'] = implode(",", $data['product_id']);
                $data['variant_list'] = implode(",", $data['variant_id']);
                $data['qty_list'] = implode(",", $data['product_qty']);
                $data['price_list'] = implode(",", $data['unit_price']);
                $data['cost'] = $data['unit_id'] = $data['purchase_unit_id'] = $data['sale_unit_id'] = 0;
            }
            elseif($data['type'] == 'digital' || $data['type'] == 'service')
                $data['cost'] = $data['unit_id'] = $data['purchase_unit_id'] = $data['sale_unit_id'] = 0;

            if(!isset($data['featured']))
                $data['featured'] = 0;

            if(!isset($data['is_embeded']))
                $data['is_embeded'] = 0;

            if(!isset($data['promotion']))
                $data['promotion'] = null;

            if(!isset($data['is_batch']))
                $data['is_batch'] = null;

            if(!isset($data['is_imei']))
                $data['is_imei'] = null;

            $data['product_details'] = str_replace('"', '@', $data['product_details']);
            $data['product_details'] = $data['product_details'];
            if($data['starting_date'])
                $data['starting_date'] = date('Y-m-d', strtotime($data['starting_date']));
            if($data['last_date'])
                $data['last_date'] = date('Y-m-d', strtotime($data['last_date']));

            $previous_images = [];
            //dealing with previous images
            if($request->prev_img) {
                foreach ($request->prev_img as $key => $prev_img) {
                    if(!in_array($prev_img, $previous_images))
                        $previous_images[] = $prev_img;
                }
                $ot_crm_product_data->image = implode(",", $previous_images);
                $ot_crm_product_data->save();
            }
            else {
                $ot_crm_product_data->image = null;
                $ot_crm_product_data->save();
            }

            //dealing with new images
            if($request->image) {
                $images = $request->image;
                $image_names = [];
                $length = count(explode(",", $ot_crm_product_data->image));
                foreach ($images as $key => $image) {
                    $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
                    /*$image = Image::make($image)->resize(512, 512);*/
                    $imageName = date("Ymdhis") . ($length + $key+1) . '.' . $ext;
                    $image->move('public/images/product', $imageName);
                    $image_names[] = $imageName;
                }
                if($ot_crm_product_data->image)
                    $data['image'] = $ot_crm_product_data->image. ',' . implode(",", $image_names);
                else
                    $data['image'] = implode(",", $image_names);
            }
            else
                $data['image'] = $ot_crm_product_data->image;

            $file = $request->file;
            if ($file) {
                $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $fileName = strtotime(date('Y-m-d H:i:s'));
                $fileName = $fileName . '.' . $ext;
                $file->move('public/product/files', $fileName);
                $data['file'] = $fileName;
            }

            $old_product_variant_ids = SaleProductVariant::where('product_id', $request->input('id'))->pluck('id')->toArray();
            $new_product_variant_ids = [];
            //dealing with product variant
            if(isset($data['is_variant'])) {
                if(isset($data['variant_option']) && isset($data['variant_value'])) {
                    $data['variant_option'] = json_encode($data['variant_option']);
                    $data['variant_value'] = json_encode($data['variant_value']);
                }
                foreach ($data['variant_name'] as $key => $variant_name) {
                    $ot_crm_variant_data = SaleVariant::firstOrCreate(['name' => $data['variant_name'][$key]]);
                    $ot_crm_product_variant_data = SaleProductVariant::where([
                                                    ['product_id', $ot_crm_product_data->id],
                                                    ['variant_id', $ot_crm_variant_data->id]
                                                ])->first();
                    if($ot_crm_product_variant_data) {
                        $ot_crm_product_variant_data->update([
                            'position' => $key+1,
                            'item_code' => $data['item_code'][$key],
                            'additional_cost' => $data['additional_cost'][$key],
                            'additional_price' => $data['additional_price'][$key]
                        ]);
                    }
                    else {
                        $ot_crm_product_variant_data = new SaleProductVariant();
                        $ot_crm_product_variant_data->product_id = $ot_crm_product_data->id;
                        $ot_crm_product_variant_data->variant_id = $ot_crm_variant_data->id;
                        $ot_crm_product_variant_data->position = $key + 1;
                        $ot_crm_product_variant_data->item_code = $data['item_code'][$key];
                        $ot_crm_product_variant_data->additional_cost = $data['additional_cost'][$key];
                        $ot_crm_product_variant_data->additional_price = $data['additional_price'][$key];
                        $ot_crm_product_variant_data->qty = 0;
                        $ot_crm_product_variant_data->save();
                    }
                    $new_product_variant_ids[] = $ot_crm_product_variant_data->id;
                }
            }
            else {
                $data['is_variant'] = null;
                $data['variant_option'] = null;
                $data['variant_value'] = null;
            }
            //deleting old product variant if not exist
            foreach ($old_product_variant_ids as $key => $product_variant_id) {
                if (!in_array($product_variant_id, $new_product_variant_ids))
                SaleProductVariant::find($product_variant_id)->delete();
            }
            if(isset($data['is_diffPrice'])) {
                foreach ($data['diff_price'] as $key => $diff_price) {
                    if($diff_price) {
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($ot_crm_product_data->id, $data['warehouse_id'][$key])->first();
                        if($ot_crm_product_warehouse_data) {
                            $ot_crm_product_warehouse_data->price = $diff_price;
                            $ot_crm_product_warehouse_data->save();
                        }
                        else {
                            SaleProductWarehouse::create([
                                "product_id" => $ot_crm_product_data->id,
                                "warehouse_id" => $data["warehouse_id"][$key],
                                "qty" => 0,
                                "price" => $diff_price
                            ]);
                        }
                    }
                }
            }
            else {
                $data['is_diffPrice'] = false;
                foreach ($data['warehouse_id'] as $key => $warehouse_id) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($ot_crm_product_data->id, $warehouse_id)->first();
                    if($ot_crm_product_warehouse_data) {
                        $ot_crm_product_warehouse_data->price = null;
                        $ot_crm_product_warehouse_data->save();
                    }
                }
            }
            $ot_crm_product_data->update($data);
            Toastr::success(_trans('response.Product updated successfully'), 'Success');
            return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $ot_crm_product_data = SaleProduct::findOrFail($id);
        $ot_crm_product_data->is_active = false;
        
        $ot_crm_product_data->save();
        Toastr::success(_trans('response.Product deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }


    public function printBarcode(Request $request)
    {
        if($request->input('data'))
            $preLoadedproduct = $this->ot_crmProductSearch($request);
        else
        $preLoadedproduct = null;
        $ot_crm_product_list_without_variant = $this->productWithoutVariant();
        $ot_crm_product_list_with_variant = $this->productWithVariant();
        $data['title'] = 'Print Barcode';
        
        return view('sale::product.product.print_barcode', compact('data','ot_crm_product_list_without_variant', 'ot_crm_product_list_with_variant', 'preLoadedproduct'));
    }

    public function ot_crmProductSearch(Request $request)
    {
        $product_code = explode("(", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $ot_crm_product_data = SaleProduct::where([
            ['code', $product_code[0] ],
            ['is_active', true]
        ])->first();
        if(!$ot_crm_product_data) {
            $ot_crm_product_data = SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
                ->select('sale_products.*', 'sale_product_variants.item_code', 'sale_product_variants.variant_id', 'sale_product_variants.additional_price')
                ->where('product_variants.item_code', $product_code[0])
                ->first();

            $variant_id = $ot_crm_product_data->variant_id;
            $additional_price = $ot_crm_product_data->additional_price;
        }
        else {
            $variant_id = '';
            $additional_price = 0;
        }
        $product[] = $ot_crm_product_data->name;
        if($ot_crm_product_data->is_variant)
            $product[] = $ot_crm_product_data->item_code;
        else
            $product[] = $ot_crm_product_data->code;
            
        $product[] = $ot_crm_product_data->price + $additional_price;
        $product[] = DNS1D::getBarcodePNGPath($ot_crm_product_data->code, $ot_crm_product_data->barcode_symbology);
        $product[] = $ot_crm_product_data->promotion_price;
        $product[] = config('currency');
        $product[] = config('currency_position');
        $product[] = $ot_crm_product_data->qty;
        $product[] = $ot_crm_product_data->id;
        $product[] = $variant_id;
        return $product;
    }
    
    public function history($id)
    {
        $data['title'] = 'Product History';
        $product_data = saleProduct::select('name', 'code')->find($id);
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();


        $sales = Sale::whereHas('saleSingleProduct', function ($q) use ($id) {
            $q->where('product_id', $id);
        })->with('warehouse', 'customer','saleSingleProduct')->orderBy('id', 'desc')->get();

        $data['sales'] = $sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'date' => $sale->created_at->format('Y-m-d'),
                'reference_no' => $sale->reference_no,
                'warehouse' => $sale->warehouse->name,
                'customer' => $sale->customer->name,
                'qty' => $sale->saleSingleProduct->qty,
                'unit_price' => $sale->saleSingleProduct->total / $sale->saleSingleProduct->qty,
                'sub_total' => $sale->saleSingleProduct->total,
            ];
        });


        $purchases = SalePurchase::whereHas('purchaseSingleProduct', function ($q) use ($id) {
            $q->where('product_id', $id);
        })->with('warehouse', 'supplier','purchaseSingleProduct')->orderBy('id', 'desc')->get();

        $data['purchases'] = $purchases->map(function ($purchase) {
            return [
                'id' => $purchase->id,
                'date' => $purchase->created_at->format('Y-m-d'),
                'reference_no' => $purchase->reference_no,
                'warehouse' => $purchase->warehouse->name,
                'supplier' => $purchase->supplier->name,
                'qty' => $purchase->purchaseSingleProduct->qty,
                'unit_price' => $purchase->purchaseSingleProduct->total / $purchase->purchaseSingleProduct->qty,
                'sub_total' => $purchase->purchaseSingleProduct->total,
            ];
        });

        // sale return 
        $sale_returns = SaleReturn::whereHas('saleReturnSingleProduct', function ($q) use ($id) {
            $q->where('product_id', $id);
        })->with('warehouse', 'customer','saleReturnSingleProduct')->orderBy('id', 'desc')->get();


        $data['sale_returns'] = $sale_returns->map(function ($sale_return) {
            return [
                'id' => $sale_return->id,
                'date' => $sale_return->created_at->format('Y-m-d'),
                'reference_no' => $sale_return->reference_no,
                'warehouse' => $sale_return->warehouse->name,
                'customer' => $sale_return->customer->name,
                'qty' => $sale_return->saleReturnSingleProduct->qty,
                'unit_price' => $sale_return->saleReturnSingleProduct->total / $sale_return->saleReturnSingleProduct->qty,
                'sub_total' => $sale_return->saleReturnSingleProduct->total,
            ];
        });


        // purchase return 
        $purchase_returns = SaleReturnPurchase::whereHas('purchaseReturnSingleProduct', function ($q) use ($id) {
            $q->where('product_id', $id);
        })->with('warehouse', 'supplier','purchaseReturnSingleProduct')->orderBy('id', 'desc')->get();

        $data['purchase_returns'] = $purchase_returns->map(function ($purchase_return) {
            return [
                'id' => $purchase_return->id,
                'date' => $purchase_return->created_at->format('Y-m-d'),
                'reference_no' => $purchase_return->reference_no,
                'warehouse' => $purchase_return->warehouse->name,
                'supplier' => $purchase_return->supplier->name,
                'qty' => $purchase_return->purchaseReturnSingleProduct->qty,
                'unit_price' => $purchase_return->purchaseReturnSingleProduct->total / $purchase_return->purchaseReturnSingleProduct->qty,
                'sub_total' => $purchase_return->purchaseReturnSingleProduct->total,
            ];
        });

        return view('sale::product.product.history',compact('data', 'product_data', 'ot_crm_warehouse_list'));
    }
}

