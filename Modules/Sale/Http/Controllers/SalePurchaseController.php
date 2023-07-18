<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\SaleTax;
use Modules\Sale\Entities\SaleUnit;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleAccount;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SalePurchase;
use Modules\Sale\Entities\SaleSupplier;
use Modules\Sale\Entities\SaleWarehouse;
use Modules\Sale\Entities\SalePosSetting;
use Modules\Sale\Entities\SaleProductBatch;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleProductVariant;
use Modules\Sale\Entities\SaleProductPurchase;
use Modules\Sale\Entities\SaleProductWarehouse;

class SalePurchaseController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        
        $data['title'] = _trans('common.Purchase List');

        $data['purchases'] = SalePurchase::with('supplier','warehouse');
        $data['warehouses'] = SaleWarehouse::where('is_active', true)->get();
        $data['accounts'] = SaleAccount::where('is_active', true)->get();
        $data['search'] = '';
        if ($search != "") {
            $data['purchases'] = $data['purchases']->where('reference_no', 'like', '%' . $search . '%');
        }
        $data['purchases'] = $data['purchases']->latest()->paginate($entries);



        return view('sale::purchase.index',compact('data'));
    }

    public function create()
    {
        $data['title'] = _trans('common.Purchase Create');
        $ot_crm_supplier_list = SaleSupplier::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $ot_crm_product_list_without_variant = $this->productWithoutVariant();
        $ot_crm_product_list_with_variant = $this->productWithVariant();
        /*$ot_crm_new_product_list_with_variant = $this->newProductWithVariant();*/
        return view('sale::purchase.create', compact('data','ot_crm_supplier_list', 'ot_crm_warehouse_list', 'ot_crm_tax_list', 'ot_crm_product_list_without_variant', 'ot_crm_product_list_with_variant'));

    }

    public function productWithoutVariant()
    {
        return SaleProduct::ActiveStandard()->select('id', 'name', 'code')
                ->whereNull('is_variant')->get();
    }

    public function productWithVariant()
    {
        return SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
            ->ActiveStandard()
            ->whereNotNull('is_variant')
            ->select('sale_products.id', 'sale_products.name', 'sale_product_variants.item_code')
            ->orderBy('position')
            ->get();
    }

    public function newProductWithVariant()
    {
        return SaleProduct::ActiveStandard()
                ->whereNotNull('is_variant')
                ->whereNotNull('variant_data')
                ->select('id', 'name', 'variant_data')
                ->get();
    }

    public function ot_crmProductSearch(Request $request)
    {
        $product_code = explode("|", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $ot_crm_product_data = SaleProduct::where([
                                ['code', $product_code[0]],
                                ['is_active', true]
                            ])
                            ->whereNull('is_variant')
                            ->first();
        if(!$ot_crm_product_data) {
            $ot_crm_product_data = SaleProduct::where([
                                ['name', $product_code[1]],
                                ['is_active', true]
                            ])
                            ->whereNotNull(['is_variant'])
                            ->first();
            $ot_crm_product_data = SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
                ->where([
                    ['sale_product_variants.item_code', $product_code[0]],
                    ['sale_products.is_active', true]
                ])
                ->whereNotNull('is_variant')
                ->select('sale_products.*', 'sale_product_variants.item_code', 'sale_product_variants.additional_cost')
                ->first();
            $ot_crm_product_data->cost += $ot_crm_product_data->additional_cost;
        }
        $product[] = $ot_crm_product_data->name;
        if($ot_crm_product_data->is_variant)
            $product[] = $ot_crm_product_data->item_code;
        else
            $product[] = $ot_crm_product_data->code;
        $product[] = $ot_crm_product_data->cost;
        
        if ($ot_crm_product_data->tax_id) {
            $ot_crm_tax_data = SaleTax::find($ot_crm_product_data->tax_id);
            $product[] = $ot_crm_tax_data->rate;
            $product[] = $ot_crm_tax_data->name;
        } else {
            $product[] = 0;
            $product[] = 'No Tax';
        }
        $product[] = $ot_crm_product_data->tax_method;

        $units = SaleUnit::where("base_unit", $ot_crm_product_data->unit_id)
                    ->orWhere('id', $ot_crm_product_data->unit_id)
                    ->get();
        $unit_name = array();
        $unit_operator = array();
        $unit_operation_value = array();
        foreach ($units as $unit) {
            if ($ot_crm_product_data->purchase_unit_id == $unit->id) {
                array_unshift($unit_name, $unit->unit_name);
                array_unshift($unit_operator, $unit->operator);
                array_unshift($unit_operation_value, $unit->operation_value);
            } else {
                $unit_name[]  = $unit->unit_name;
                $unit_operator[] = $unit->operator;
                $unit_operation_value[] = $unit->operation_value;
            }
        }
        
        $product[] = implode(",", $unit_name) . ',';
        $product[] = implode(",", $unit_operator) . ',';
        $product[] = implode(",", $unit_operation_value) . ',';
        $product[] = $ot_crm_product_data->id;
        $product[] = $ot_crm_product_data->is_batch;
        $product[] = $ot_crm_product_data->is_imei;
        return $product;
    }

    public function store(Request $request)
    {
        $data = $request->except('document');
        $data['user_id'] = Auth::id();
        $data['reference_no'] = 'pr-' . date("Ymd") . '-'. date("his");
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ]
            );
            if ($v->fails())
                return redirect()->back()->withErrors($v->errors());

            $documentName = $document->getClientOriginalName();
            $document->move('public/documents/purchase', $documentName);
            $data['document'] = $documentName;
        }
        if(isset($data['created_at']))
            $data['created_at'] = date("Y-m-d H:i:s", strtotime($data['created_at']));
        else
            $data['created_at'] = date("Y-m-d H:i:s");
        $ot_crm_purchase_data = SalePurchase::create($data);
        $product_id = $data['product_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $recieved = $data['recieved'];
        $batch_no = $data['batch_no'];
        $expired_date = $data['expired_date'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $imei_numbers = $data['imei_number'];
        $product_purchase = [];

        foreach ($product_id as $i => $id) {
            $ot_crm_purchase_unit_data  = SaleUnit::where('unit_name', $purchase_unit[$i])->first();

            if ($ot_crm_purchase_unit_data->operator == '*') {
                $quantity = $recieved[$i] * $ot_crm_purchase_unit_data->operation_value;
            } else {
                $quantity = $recieved[$i] / $ot_crm_purchase_unit_data->operation_value;
            }
            $ot_crm_product_data = SaleProduct::find($id);

            //dealing with product barch
            if($batch_no[$i]) {
                $product_batch_data = SaleProductBatch::where([
                                        ['product_id', $ot_crm_product_data->id],
                                        ['batch_no', $batch_no[$i]]
                                    ])->first();
                if($product_batch_data) {
                    $product_batch_data->expired_date = $expired_date[$i];
                    $product_batch_data->qty += $quantity;
                    $product_batch_data->save();
                }
                else {
                    $product_batch_data = SaleProductBatch::create([
                                            'product_id' => $ot_crm_product_data->id,
                                            'batch_no' => $batch_no[$i],
                                            'expired_date' => $expired_date[$i],
                                            'qty' => $quantity
                                        ]);   
                }
                $product_purchase['product_batch_id'] = $product_batch_data->id;
            }
            else
                $product_purchase['product_batch_id'] = null;

            if($ot_crm_product_data->is_variant) {
                $ot_crm_product_variant_data = SaleProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($ot_crm_product_data->id, $product_code[$i])->first();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $id],
                    ['variant_id', $ot_crm_product_variant_data->variant_id],
                    ['warehouse_id', $data['warehouse_id']]
                ])->first();
                $product_purchase['variant_id'] = $ot_crm_product_variant_data->variant_id;
                //add quantity to product variant table
                $ot_crm_product_variant_data->qty += $quantity;
                $ot_crm_product_variant_data->save();
            }
            else {
                $product_purchase['variant_id'] = null;
                if($product_purchase['product_batch_id']) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $id],
                        ['product_batch_id', $product_purchase['product_batch_id'] ],
                        ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                }
                else {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $id],
                        ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                }
            }
            //add quantity to product table
            $ot_crm_product_data->qty = $ot_crm_product_data->qty + $quantity;
            $ot_crm_product_data->save();
            //add quantity to warehouse
            if ($ot_crm_product_warehouse_data) {
                $ot_crm_product_warehouse_data->qty = $ot_crm_product_warehouse_data->qty + $quantity;
                $ot_crm_product_warehouse_data->product_batch_id = $product_purchase['product_batch_id'];
            } 
            else {
                $ot_crm_product_warehouse_data = new SaleProductWarehouse();
                $ot_crm_product_warehouse_data->product_id = $id;
                $ot_crm_product_warehouse_data->product_batch_id = $product_purchase['product_batch_id'];
                $ot_crm_product_warehouse_data->warehouse_id = $data['warehouse_id'];
                $ot_crm_product_warehouse_data->qty = $quantity;
                if($ot_crm_product_data->is_variant)
                    $ot_crm_product_warehouse_data->variant_id = $ot_crm_product_variant_data->variant_id;
            }
            //added imei numbers to product_warehouse table
            if($imei_numbers[$i]) {
                if($ot_crm_product_warehouse_data->imei_number)
                    $ot_crm_product_warehouse_data->imei_number .= ',' . $imei_numbers[$i];
                else
                    $ot_crm_product_warehouse_data->imei_number = $imei_numbers[$i];
            }
            $ot_crm_product_warehouse_data->save();

            $product_purchase['purchase_id'] = $ot_crm_purchase_data->id ;
            $product_purchase['product_id'] = $id;
            $product_purchase['imei_number'] = $imei_numbers[$i];
            $product_purchase['qty'] = $qty[$i];
            $product_purchase['recieved'] = $recieved[$i];
            $product_purchase['purchase_unit_id'] = $ot_crm_purchase_unit_data->id;
            $product_purchase['net_unit_cost'] = $net_unit_cost[$i];
            $product_purchase['discount'] = $discount[$i];
            $product_purchase['tax_rate'] = $tax_rate[$i];
            $product_purchase['tax'] = $tax[$i];
            $product_purchase['total'] = $total[$i];
            SaleProductPurchase::create($product_purchase);
        }
        Toastr::success('Purchase created successfully', 'Success');
        return redirect()->route('salePurchase.index');
    }

    public function productPurchaseData($id)
    {
        $ot_crm_product_purchase_data = SaleProductPurchase::where('purchase_id', $id)->get();
        foreach ($ot_crm_product_purchase_data as $key => $product_purchase_data) {
            $product = SaleProduct::find($product_purchase_data->product_id);
            $unit = SaleUnit::find($product_purchase_data->purchase_unit_id);
            if($product_purchase_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::FindExactProduct($product->id, $product_purchase_data->variant_id)->select('item_code')->first();
                $product->code = $ot_crm_product_variant_data->item_code;
            }
            if($product_purchase_data->product_batch_id) {
                $product_batch_data = SaleProductBatch::select('batch_no')->find($product_purchase_data->product_batch_id);
                $product_purchase[7][$key] = $product_batch_data->batch_no;
            }
            else
                $product_purchase[7][$key] = 'N/A';
            $product_purchase[0][$key] = $product->name . ' [' . $product->code.']';
            if($product_purchase_data->imei_number) {
                $product_purchase[0][$key] .= '<br>IMEI or Serial Number: '. $product_purchase_data->imei_number;
            }
            $product_purchase[1][$key] = $product_purchase_data->qty;
            $product_purchase[2][$key] = $unit->unit_code;
            $product_purchase[3][$key] = $product_purchase_data->tax;
            $product_purchase[4][$key] = $product_purchase_data->tax_rate;
            $product_purchase[5][$key] = $product_purchase_data->discount;
            $product_purchase[6][$key] = $product_purchase_data->total;
        }
        return $product_purchase;
    }

    public function purchaseByCsv()
    {
        $ot_crm_supplier_list = SaleSupplier::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $data['title'] = _trans('common.Import Purchase');
        return view('sale::purchase.import', compact('data','ot_crm_supplier_list', 'ot_crm_warehouse_list', 'ot_crm_tax_list'));
    }

    public function importPurchase(Request $request)
    {

        try{
            //get the file
            $upload=$request->file('file');
            $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
            //checking if this is a CSV file
            if($ext != 'csv')
                return redirect()->back()->with('message', 'Please upload a CSV file');

            $filePath=$upload->getRealPath();
            $file_handle = fopen($filePath, 'r');
            $i = 0;
            //validate the file
            while (!feof($file_handle) ) {
                $current_line = fgetcsv($file_handle);
                if($current_line && $i > 0){
                    $product_data[] = SaleProduct::where('code', $current_line[0])->first();
                    if(!$product_data[$i-1])
                        return redirect()->back()->with('message', 'Product with this code '.$current_line[0].' does not exist!');
                    $unit[] = SaleUnit::where('unit_code', $current_line[2])->first();
                    if(!$unit[$i-1])
                    
                    return redirect()->back()->with('message', 'Purchase unit does not exist!');
                    if(strtolower($current_line[5]) != "no tax"){
                        $tax[] = SaleTax::where('name', $current_line[5])->first();
                        if(!$tax[$i-1])
                            return redirect()->back()->with('message', 'Tax name does not exist!');
                    }
                    else
                        $tax[$i-1]['rate'] = 0;

                    $qty[] = $current_line[1];
                    $cost[] = $current_line[3];
                    $discount[] = $current_line[4];
                }
                $i++;
            }

            $data = $request->except('file');
            $data['reference_no'] = 'pr-' . date("Ymd") . '-'. date("his");
            $document = $request->document;

            if ($document) {
                $v = Validator::make(
                    [
                        'extension' => strtolower($request->document->getClientOriginalExtension()),
                    ],
                    [
                        'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                    ]
                );
                if ($v->fails())
                    return redirect()->back()->withErrors($v->errors());

                $ext = pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION);
                $documentName = $data['reference_no'] . '.' . $ext;
                $document->move('public/documents/purchase', $documentName);
                $data['document'] = $documentName;
            }
            $item = 0;
            $grand_total = $data['shipping_cost'];
            $data['user_id'] = Auth::id();
            SalePurchase::create($data);
            $ot_crm_purchase_data = SalePurchase::latest()->first();
            
            foreach ($product_data as $key => $product) {
                if($product['tax_method'] == 1){
                    $net_unit_cost = $cost[$key] - $discount[$key];
                    $product_tax = $net_unit_cost * ($tax[$key]['rate'] / 100) * $qty[$key];
                    $total = ($net_unit_cost * $qty[$key]) + $product_tax;
                }
                elseif($product['tax_method'] == 2){
                    $net_unit_cost = (100 / (100 + $tax[$key]['rate'])) * ($cost[$key] - $discount[$key]);
                    $product_tax = ($cost[$key] - $discount[$key] - $net_unit_cost) * $qty[$key];
                    $total = ($cost[$key] - $discount[$key]) * $qty[$key];
                }
                if($data['status'] == 1){
                    if($unit[$key]['operator'] == '*')
                        $quantity = $qty[$key] * $unit[$key]['operation_value'];
                    elseif($unit[$key]['operator'] == '/')
                        $quantity = $qty[$key] / $unit[$key]['operation_value'];

                    $product['qty'] += $quantity;
                    $product_warehouse = SaleProductWarehouse::where([
                        ['product_id', $product['id']],
                        ['warehouse_id', $data['warehouse_id']]
                    ])->first();
                    if($product_warehouse) {
                        $product_warehouse->qty += $quantity;
                        $product_warehouse->save();
                    }
                    else {
                        $ot_crm_product_warehouse_data = new SaleProductWarehouse();
                        $ot_crm_product_warehouse_data->product_id = $product['id'];
                        $ot_crm_product_warehouse_data->warehouse_id = $data['warehouse_id'];
                        $ot_crm_product_warehouse_data->qty = $quantity;
                        $ot_crm_product_warehouse_data->save();
                    }
                    $product->save();
                }
                $product_purchase = new SaleProductPurchase();
                $product_purchase->purchase_id = $ot_crm_purchase_data->id;
                $product_purchase->product_id = $product['id'];
                $product_purchase->qty = $qty[$key];
                if($data['status'] == 1)
                    $product_purchase->recieved = $qty[$key];
                else
                    $product_purchase->recieved = 0;
                $product_purchase->purchase_unit_id = $unit[$key]['id'];
                $product_purchase->net_unit_cost = number_format((float)$net_unit_cost, 2, '.', '');
                $product_purchase->discount = $discount[$key] * $qty[$key];
                $product_purchase->tax_rate = $tax[$key]['rate'];
                $product_purchase->tax = number_format((float)$product_tax, 2, '.', '');
                $product_purchase->total = number_format((float)$total, 2, '.', '');
                $product_purchase->save();
                $ot_crm_purchase_data->total_qty += $qty[$key];
                $ot_crm_purchase_data->total_discount += $discount[$key] * $qty[$key];
                $ot_crm_purchase_data->total_tax += number_format((float)$product_tax, 2, '.', '');
                $ot_crm_purchase_data->total_cost += number_format((float)$total, 2, '.', '');
            }

            $ot_crm_purchase_data->item = $key + 1;
            $ot_crm_purchase_data->order_tax = ($ot_crm_purchase_data->total_cost - $ot_crm_purchase_data->order_discount) * ($data['order_tax_rate'] / 100);
            $ot_crm_purchase_data->grand_total = ($ot_crm_purchase_data->total_cost + $ot_crm_purchase_data->order_tax + $ot_crm_purchase_data->shipping_cost) - $ot_crm_purchase_data->order_discount;
            $ot_crm_purchase_data->save();
            Toastr::success('Purchase Imported successfully', 'Success');
            return redirect()->route('salePurchase.index');
        }catch(\Exception $e){
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $ot_crm_supplier_list = SaleSupplier::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $ot_crm_product_list_without_variant = $this->productWithoutVariant();
        $ot_crm_product_list_with_variant = $this->productWithVariant();
        $ot_crm_purchase_data = SalePurchase::find($id);
        $ot_crm_product_purchase_data = SaleProductPurchase::where('purchase_id', $id)->get();
        $data['title'] = _trans('common.Purchase Edit');
        return view('sale::purchase.edit', compact('data','ot_crm_warehouse_list', 'ot_crm_supplier_list', 'ot_crm_product_list_without_variant', 'ot_crm_product_list_with_variant', 'ot_crm_tax_list', 'ot_crm_purchase_data', 'ot_crm_product_purchase_data'));

        
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails())
                return redirect()->back()->withErrors($v->errors());

            $documentName = $document->getClientOriginalName();
            $document->move('public/purchase/documents', $documentName);
            $data['document'] = $documentName;
        }
        $balance = $data['grand_total'] - $data['paid_amount'];
        if ($balance < 0 || $balance > 0) {
            $data['payment_status'] = 1;
        } else {
            $data['payment_status'] = 2;
        }
        $ot_crm_purchase_data = SalePurchase::find($id);
        $ot_crm_product_purchase_data = SaleProductPurchase::where('purchase_id', $id)->get();

        $data['created_at'] = date("Y-m-d", strtotime(str_replace("/", "-", $data['created_at'])));
        $product_id = $data['product_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $recieved = $data['recieved'];
        $batch_no = $data['batch_no'];
        $expired_date = $data['expired_date'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $imei_number = $new_imei_number = $data['imei_number'];
        $product_purchase = [];

        foreach ($ot_crm_product_purchase_data as $product_purchase_data) {

            $old_recieved_value = $product_purchase_data->recieved;
            $ot_crm_purchase_unit_data = SaleUnit::find($product_purchase_data->purchase_unit_id);
            
            if ($ot_crm_purchase_unit_data->operator == '*') {
                $old_recieved_value = $old_recieved_value * $ot_crm_purchase_unit_data->operation_value;
            } else {
                $old_recieved_value = $old_recieved_value / $ot_crm_purchase_unit_data->operation_value;
            }
            $ot_crm_product_data = SaleProduct::find($product_purchase_data->product_id);
            if($ot_crm_product_data->is_variant) {
                $ot_crm_product_variant_data = SaleProductVariant::select('id', 'variant_id', 'qty')->FindExactProduct($ot_crm_product_data->id, $product_purchase_data->variant_id)->first();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $ot_crm_product_data->id],
                    ['variant_id', $product_purchase_data->variant_id],
                    ['warehouse_id', $ot_crm_purchase_data->warehouse_id]
                ])->first();
                $ot_crm_product_variant_data->qty -= $old_recieved_value;
                $ot_crm_product_variant_data->save();
            }
            elseif($product_purchase_data->product_batch_id) {
                $product_batch_data = SaleProductBatch::find($product_purchase_data->product_batch_id);
                $product_batch_data->qty -= $old_recieved_value;
                $product_batch_data->save();

                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $product_purchase_data->product_id],
                    ['product_batch_id', $product_purchase_data->product_batch_id],
                    ['warehouse_id', $ot_crm_purchase_data->warehouse_id],
                ])->first();
            }
            else {
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $product_purchase_data->product_id],
                    ['warehouse_id', $ot_crm_purchase_data->warehouse_id],
                ])->first();
            }
            if($product_purchase_data->imei_number) {
                $position = array_search($ot_crm_product_data->id, $product_id);
                if($imei_number[$position]) {
                    $prev_imei_numbers = explode(",", $product_purchase_data->imei_number);
                    $new_imei_numbers = explode(",", $imei_number[$position]);
                    foreach ($prev_imei_numbers as $prev_imei_number) {
                        if(($pos = array_search($prev_imei_number, $new_imei_numbers)) !== false) {
                            unset($new_imei_numbers[$pos]);
                        }
                    }
                    $new_imei_number[$position] = implode(",", $new_imei_numbers);
                }
            }
            $ot_crm_product_data->qty -= $old_recieved_value;
            $ot_crm_product_warehouse_data->qty -= $old_recieved_value;
            $ot_crm_product_warehouse_data->save();
            $ot_crm_product_data->save();
            $product_purchase_data->delete();
        }

        foreach ($product_id as $key => $pro_id) {
            $ot_crm_purchase_unit_data = SaleUnit::where('unit_name', $purchase_unit[$key])->first();
            if ($ot_crm_purchase_unit_data->operator == '*') {
                $new_recieved_value = $recieved[$key] * $ot_crm_purchase_unit_data->operation_value;
            } else {
                $new_recieved_value = $recieved[$key] / $ot_crm_purchase_unit_data->operation_value;
            }

            $ot_crm_product_data = SaleProduct::find($pro_id);
            //dealing with product barch
            if($batch_no[$key]) {
                $product_batch_data = SaleProductBatch::where([
                                        ['product_id', $ot_crm_product_data->id],
                                        ['batch_no', $batch_no[$key]]
                                    ])->first();
                if($product_batch_data) {
                    $product_batch_data->qty += $new_recieved_value;
                    $product_batch_data->expired_date = $expired_date[$key];
                    $product_batch_data->save();
                }
                else {
                    $product_batch_data = SaleProductBatch::create([
                                            'product_id' => $ot_crm_product_data->id,
                                            'batch_no' => $batch_no[$key],
                                            'expired_date' => $expired_date[$key],
                                            'qty' => $new_recieved_value
                                        ]);   
                }
                $product_purchase['product_batch_id'] = $product_batch_data->id;
            }
            else
                $product_purchase['product_batch_id'] = null;

            if($ot_crm_product_data->is_variant) {
                $ot_crm_product_variant_data = SaleProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $pro_id],
                    ['variant_id', $ot_crm_product_variant_data->variant_id],
                    ['warehouse_id', $data['warehouse_id']]
                ])->first();
                $product_purchase['variant_id'] = $ot_crm_product_variant_data->variant_id;
                //add quantity to product variant table
                $ot_crm_product_variant_data->qty += $new_recieved_value;
                $ot_crm_product_variant_data->save();
            }
            else {
                $product_purchase['variant_id'] = null;
                if($product_purchase['product_batch_id']) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $pro_id],
                        ['product_batch_id', $product_purchase['product_batch_id'] ],
                        ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                }
                else {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $pro_id],
                        ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                }
            }

            $ot_crm_product_data->qty += $new_recieved_value;
            if($ot_crm_product_warehouse_data){
                $ot_crm_product_warehouse_data->qty += $new_recieved_value;
                $ot_crm_product_warehouse_data->save();
            }
            else {
                $ot_crm_product_warehouse_data = new SaleProductWarehouse();
                $ot_crm_product_warehouse_data->product_id = $pro_id;
                $ot_crm_product_warehouse_data->product_batch_id = $product_purchase['product_batch_id'];
                if($ot_crm_product_data->is_variant)
                    $ot_crm_product_warehouse_data->variant_id = $ot_crm_product_variant_data->variant_id;
                $ot_crm_product_warehouse_data->warehouse_id = $data['warehouse_id'];
                $ot_crm_product_warehouse_data->qty = $new_recieved_value;
            }
            //dealing with imei numbers
            if($imei_number[$key]) {
                if($ot_crm_product_warehouse_data->imei_number) {
                    $ot_crm_product_warehouse_data->imei_number .= ',' . $new_imei_number[$key];
                }
                else {
                    $ot_crm_product_warehouse_data->imei_number = $new_imei_number[$key];
                }
            }

            $ot_crm_product_data->save();
            $ot_crm_product_warehouse_data->save();

            $product_purchase['purchase_id'] = $id ;
            $product_purchase['product_id'] = $pro_id;
            $product_purchase['qty'] = $qty[$key];
            $product_purchase['recieved'] = $recieved[$key];
            $product_purchase['purchase_unit_id'] = $ot_crm_purchase_unit_data->id;
            $product_purchase['net_unit_cost'] = $net_unit_cost[$key];
            $product_purchase['discount'] = $discount[$key];
            $product_purchase['tax_rate'] = $tax_rate[$key];
            $product_purchase['tax'] = $tax[$key];
            $product_purchase['total'] = $total[$key];
            $product_purchase['imei_number'] = $imei_number[$key];
            SaleProductPurchase::create($product_purchase);
        }

        $ot_crm_purchase_data->update($data);
        Toastr::success('Purchase updated successfully', 'Success');
        return redirect()->route('salePurchase.index');
    }

    public function addPayment(Request $request)
    {
        $data = $request->all();
        $ot_crm_purchase_data = SalePurchase::find($data['purchase_id']);
        $ot_crm_purchase_data->paid_amount += $data['amount'];
        $balance = $ot_crm_purchase_data->grand_total - $ot_crm_purchase_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $ot_crm_purchase_data->payment_status = 1;
        elseif ($balance == 0)
            $ot_crm_purchase_data->payment_status = 2;
        $ot_crm_purchase_data->save();

        if($data['paid_by_id'] == 1)
            $paying_method = 'Cash';
        else
            $paying_method = 'Cheque';

        $ot_crm_payment_data = new SalePayment();
        $ot_crm_payment_data->user_id = Auth::id();
        $ot_crm_payment_data->purchase_id = $ot_crm_purchase_data->id;
        $ot_crm_payment_data->account_id = $data['account_id'];
        $ot_crm_payment_data->payment_reference = 'ppr-' . date("Ymd") . '-'. date("his");
        $ot_crm_payment_data->amount = $data['amount'];
        $ot_crm_payment_data->change = $data['paying_amount'] - $data['amount'];
        $ot_crm_payment_data->paying_method = $paying_method;
        $ot_crm_payment_data->payment_note = $data['payment_note'];
        $ot_crm_payment_data->save();

        $ot_crm_payment_data = SalePayment::latest()->first();
        $data['payment_id'] = $ot_crm_payment_data->id;
        
        if ($paying_method == 'Cheque') {
            SalePaymentWithCheque::create($data);
        }
        Toastr::success('Operation Successful', 'Success');
        return redirect()->route('salePurchase.index');
    }

    public function getPayment($id)
    {
        $data = SalePayment::with('account')->where('purchase_id', $id)->get();
        $payments = $data->map(function ($payment) {
            return [
                'date' => $payment->created_at->format('d-m-Y'),
                'payment_reference' => $payment->payment_reference,
                'paid_amount' => $payment->amount,
                'paying_method' => $payment->paying_method,
                'payment_id' => $payment->id,
                'payment_note' => $payment->payment_note,
                'cheque_no' => $payment->cheque_no,
                'change' => $payment->change,
                'paying_amount' =>  $payment->amount + $payment->change,
                'created_at' => $payment->created_at->format('d-m-Y h:i A'),
                'account_name' => $payment->account->name,
                'account_id' => $payment->account->id,
            ];
        });
        return $payments;
    }

    public function updatePayment(Request $request)
    {
        $data = $request->all();
        $ot_crm_payment_data = SalePayment::find($data['payment_id']);
        $ot_crm_purchase_data = SalePurchase::find($ot_crm_payment_data->purchase_id);
        //updating purchase table
        $amount_dif = $ot_crm_payment_data->amount - $data['edit_amount'];
        $ot_crm_purchase_data->paid_amount = $ot_crm_purchase_data->paid_amount - $amount_dif;
        $balance = $ot_crm_purchase_data->grand_total - $ot_crm_purchase_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $ot_crm_purchase_data->payment_status = 1;
        elseif ($balance == 0)
            $ot_crm_purchase_data->payment_status = 2;
        $ot_crm_purchase_data->save();

        //updating payment data
        $ot_crm_payment_data->account_id = $data['account_id'];
        $ot_crm_payment_data->amount = $data['edit_amount'];
        $ot_crm_payment_data->change = $data['edit_paying_amount'] - $data['edit_amount'];
        $ot_crm_payment_data->payment_note = $data['edit_payment_note'];
        if($data['edit_paid_by_id'] == 1)
            $ot_crm_payment_data->paying_method = 'Cash';
        elseif ($data['edit_paid_by_id'] == 2)
            $ot_crm_payment_data->paying_method = 'Gift Card';
        elseif ($data['edit_paid_by_id'] == 3){
            $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
            \Stripe\Stripe::setApiKey($ot_crm_pos_setting_data->stripe_secret_key);
            $token = $data['stripeToken'];
            $amount = $data['edit_amount'];
            if($ot_crm_payment_data->paying_method == 'Credit Card'){
                $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $ot_crm_payment_data->id)->first();

                \Stripe\Refund::create(array(
                  "charge" => $ot_crm_payment_with_credit_card_data->charge_id,
                ));

                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'source' => $token,
                ]);

                $ot_crm_payment_with_credit_card_data->charge_id = $charge->id;
                $ot_crm_payment_with_credit_card_data->save();
            }
            else{
                // Charge the Customer
                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'source' => $token,
                ]);

                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            }
            $ot_crm_payment_data->paying_method = 'Credit Card';
        }         
        else{
            if($ot_crm_payment_data->paying_method == 'Cheque'){
                $ot_crm_payment_data->paying_method = 'Cheque';
                $ot_crm_payment_cheque_data = SalePaymentWithCheque::where('payment_id', $data['payment_id'])->first();
                $ot_crm_payment_cheque_data->cheque_no = $data['edit_cheque_no'];
                $ot_crm_payment_cheque_data->save(); 
            }
            else{
                $ot_crm_payment_data->paying_method = 'Cheque';
                $data['cheque_no'] = $data['edit_cheque_no'];
                SalePaymentWithCheque::create($data);
            }
        }
        $ot_crm_payment_data->save();
        Toastr::success('Operation Successful', 'Success');
        return redirect()->route('salePurchase.index');
    }

    public function deletePayment(Request $request)
    {
        $ot_crm_payment_data = SalePayment::find($request['id']);
        $ot_crm_purchase_data = SalePurchase::where('id', $ot_crm_payment_data->purchase_id)->first();
        $ot_crm_purchase_data->paid_amount -= $ot_crm_payment_data->amount;
        $balance = $ot_crm_purchase_data->grand_total - $ot_crm_purchase_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $ot_crm_purchase_data->payment_status = 1;
        elseif ($balance == 0)
            $ot_crm_purchase_data->payment_status = 2;
        $ot_crm_purchase_data->save();

        if($ot_crm_payment_data->paying_method == 'Credit Card'){
            $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $request['id'])->first();
            $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
            \Stripe\Stripe::setApiKey($ot_crm_pos_setting_data->stripe_secret_key);
            \Stripe\Refund::create(array(
              "charge" => $ot_crm_payment_with_credit_card_data->charge_id,
            ));

            $ot_crm_payment_with_credit_card_data->delete();
        }
        elseif ($ot_crm_payment_data->paying_method == 'Cheque') {
            $ot_crm_payment_cheque_data = SalePaymentWithCheque::where('payment_id', $request['id'])->first();
            $ot_crm_payment_cheque_data->delete();
        }
        $ot_crm_payment_data->delete();
        Toastr::success('Operation Successful', 'Success');
        return redirect()->route('salePurchase.index');
    }

    public function deleteBySelection(Request $request)
    {
        $purchase_id = $request['purchaseIdArray'];
        foreach ($purchase_id as $id) {
            $ot_crm_purchase_data = SalePurchase::find($id);
            $ot_crm_product_purchase_data = SaleProductPurchase::where('purchase_id', $id)->get();
            $ot_crm_payment_data = SalePayment::where('purchase_id', $id)->get();
            foreach ($ot_crm_product_purchase_data as $product_purchase_data) {
                $ot_crm_purchase_unit_data = SaleUnit::find($product_purchase_data->purchase_unit_id);
                if ($ot_crm_purchase_unit_data->operator == '*')
                    $recieved_qty = $product_purchase_data->recieved * $ot_crm_purchase_unit_data->operation_value;
                else
                    $recieved_qty = $product_purchase_data->recieved / $ot_crm_purchase_unit_data->operation_value;

                $ot_crm_product_data = SaleProduct::find($product_purchase_data->product_id);
                if($product_purchase_data->variant_id) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($ot_crm_product_data->id, $product_purchase_data->variant_id)->first();
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_purchase_data->product_id, $product_purchase_data->variant_id, $ot_crm_purchase_data->warehouse_id)
                        ->first();
                    $ot_crm_product_variant_data->qty -= $recieved_qty;
                    $ot_crm_product_variant_data->save();
                }
                elseif($product_purchase_data->product_batch_id) {
                    $ot_crm_product_batch_data = SaleProductBatch::find($product_purchase_data->product_batch_id);
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_purchase_data->product_batch_id],
                        ['warehouse_id', $ot_crm_purchase_data->warehouse_id]
                    ])->first();

                    $ot_crm_product_batch_data->qty -= $recieved_qty;
                    $ot_crm_product_batch_data->save();
                }
                else {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_purchase_data->product_id, $ot_crm_purchase_data->warehouse_id)
                        ->first();
                }

                $ot_crm_product_data->qty -= $recieved_qty;
                $ot_crm_product_warehouse_data->qty -= $recieved_qty;
                
                $ot_crm_product_warehouse_data->save();
                $ot_crm_product_data->save();
                $product_purchase_data->delete();
            }
            foreach ($ot_crm_payment_data as $payment_data) {
                if($payment_data->paying_method == "Cheque"){
                    $payment_with_cheque_data = SalePaymentWithCheque::where('payment_id', $payment_data->id)->first();
                    $payment_with_cheque_data->delete();
                }
                elseif($payment_data->paying_method == "Credit Card"){
                    $payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment_data->id)->first();
                    $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
                    \Stripe\Stripe::setApiKey($ot_crm_pos_setting_data->stripe_secret_key);
                    \Stripe\Refund::create(array(
                      "charge" => $payment_with_credit_card_data->charge_id,
                    ));

                    $payment_with_credit_card_data->delete();
                }
                $payment_data->delete();
            }

            $ot_crm_purchase_data->delete();
        }
        return 'Purchase deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_purchase_data = SalePurchase::find($id);
        $ot_crm_product_purchase_data = SaleProductPurchase::where('purchase_id', $id)->get();
        $ot_crm_payment_data = SalePayment::where('purchase_id', $id)->get();
        foreach ($ot_crm_product_purchase_data as $product_purchase_data) {
            $ot_crm_purchase_unit_data = SaleUnit::find($product_purchase_data->purchase_unit_id);
            if ($ot_crm_purchase_unit_data->operator == '*')
                $recieved_qty = $product_purchase_data->recieved * $ot_crm_purchase_unit_data->operation_value;
            else
                $recieved_qty = $product_purchase_data->recieved / $ot_crm_purchase_unit_data->operation_value;

            $ot_crm_product_data = SaleProduct::find($product_purchase_data->product_id);
            if($product_purchase_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($ot_crm_product_data->id, $product_purchase_data->variant_id)->first();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_purchase_data->product_id, $product_purchase_data->variant_id, $ot_crm_purchase_data->warehouse_id)
                    ->first();
                $ot_crm_product_variant_data->qty -= $recieved_qty;
                $ot_crm_product_variant_data->save();
            }
            elseif($product_purchase_data->product_batch_id) {
                $ot_crm_product_batch_data = SaleProductBatch::find($product_purchase_data->product_batch_id);
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_batch_id', $product_purchase_data->product_batch_id],
                    ['warehouse_id', $ot_crm_purchase_data->warehouse_id]
                ])->first();

                $ot_crm_product_batch_data->qty -= $recieved_qty;
                $ot_crm_product_batch_data->save();
            }
            else {
                $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_purchase_data->product_id, $ot_crm_purchase_data->warehouse_id)
                    ->first();
            }
            //deduct imei number if available
            if($product_purchase_data->imei_number) {
                $imei_numbers = explode(",", $product_purchase_data->imei_number);
                $all_imei_numbers = explode(",", $ot_crm_product_warehouse_data->imei_number);
                foreach ($imei_numbers as $number) {
                    if (($j = array_search($number, $all_imei_numbers)) !== false) {
                        unset($all_imei_numbers[$j]);
                    }
                }
                $ot_crm_product_warehouse_data->imei_number = implode(",", $all_imei_numbers);
            }
            
            $ot_crm_product_data->qty -= $recieved_qty;
            $ot_crm_product_warehouse_data->qty -= $recieved_qty;

            $ot_crm_product_warehouse_data->save();
            $ot_crm_product_data->save();
            $product_purchase_data->delete();
        }
        foreach ($ot_crm_payment_data as $payment_data) {
            if($payment_data->paying_method == "Cheque"){
                $payment_with_cheque_data = SalePaymentWithCheque::where('payment_id', $payment_data->id)->first();
                $payment_with_cheque_data->delete();
            }
            elseif($payment_data->paying_method == "Credit Card"){
                $payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment_data->id)->first();
                $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
                \Stripe\Stripe::setApiKey($ot_crm_pos_setting_data->stripe_secret_key);
                \Stripe\Refund::create(array(
                    "charge" => $payment_with_credit_card_data->charge_id,
                ));

                $payment_with_credit_card_data->delete();
            }
            $payment_data->delete();
        }

        $ot_crm_purchase_data->delete();
        Toastr::success(_trans('response.Purchase deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
        
    }
}
