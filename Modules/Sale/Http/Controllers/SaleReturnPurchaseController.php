<?php

namespace Modules\Sale\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\SaleTax;
use Modules\Sale\Entities\SaleUnit;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleAccount;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleVariant;
use Modules\Sale\Entities\SalePurchase;
use Modules\Sale\Entities\SaleSupplier;
use Modules\Sale\Entities\SaleWarehouse;
use Modules\Sale\Entities\SaleProductBatch;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleProductVariant;
use Modules\Sale\Entities\SaleReturnPurchase;
use Modules\Sale\Entities\SaleProductPurchase;
use Modules\Sale\Entities\SaleProductWarehouse;
use Modules\Sale\Entities\SalePurchaseProductReturn;

class SaleReturnPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] = 'Purchase Return List';
        $data['warehouses'] = SaleWarehouse::where('is_active', true)->get();
        $data['search'] = '';
        $data['purchaseReturns'] = SaleReturnPurchase::with('supplier','user','warehouse');
        if ($search != "") {
            $data['purchaseReturns'] = $data['purchaseReturns']->where('reference_no', 'like', '%' . $search . '%');
        }
        $data['purchaseReturns'] = $data['purchaseReturns']->latest()->paginate($entries);

        return view('sale::return.purchase.index',compact( 'data'));
    }

    public function create(Request $request)
    {
        $ot_crm_purchase_data = SalePurchase::select('id')->where('reference_no', $request->input('reference_no'))->first();
        if($ot_crm_purchase_data == null){
            Toastr::error(_trans('common.Invalid Reference No'), 'Error');
            return redirect()->back();
        }
        $ot_crm_product_purchase_data = SaleProductPurchase::where('purchase_id', $ot_crm_purchase_data->id)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active',true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active',true)->get();
        $ot_crm_account_list = SaleAccount::where('is_active',true)->get();
        $data['title'] = 'Purchase Return ';
        return view('sale::return.purchase.create', compact('data','ot_crm_warehouse_list', 'ot_crm_tax_list', 'ot_crm_account_list', 'ot_crm_purchase_data', 'ot_crm_product_purchase_data'));
    }

    public function getProduct($id)
    {
        //retrieve data of product without variant
        $ot_crm_product_warehouse_data = DB::table('sale_products')
            ->join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
            ->select('sale_products.code', 'sale_products.name', 'sale_products.type', 'sale_product_warehouses.qty')
            ->where([
                ['sale_product_warehouses.warehouse_id', $id],
                ['sale_products.is_active', true]
            ])
            ->whereNull('sale_product_warehouses.variant_id')
            ->whereNull('sale_product_warehouses.product_batch_id')
            ->get();

        config()->set('database.connections.mysql.strict', false);
        DB::reconnect(); //important as the existing connection if any would be in strict mode

        //retrieve data of product with batch
        $ot_crm_product_with_batch_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
        ->where([
            ['sale_products.is_active', true],
            ['sale_product_warehouses.warehouse_id', $id],
        ])
        ->whereNull('sale_product_warehouses.variant_id')
        ->whereNotNull('sale_product_warehouses.product_batch_id')
        ->select('sale_product_warehouses.*')
        ->groupBy('sale_product_warehouses.product_id')
        ->get();

        //now changing back the strict ON
        config()->set('database.connections.mysql.strict', true);
        \DB::reconnect();

        //retrieve data of product with variant
        $ot_crm_product_with_variant_warehouse_data = DB::table('sale_products')
            ->join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
            ->select('sale_products.id', 'sale_products.code', 'sale_products.name', 'sale_products.type', 'sale_product_warehouses.qty', 'sale_product_warehouses.variant_id')
            ->where([
                ['sale_product_warehouses.warehouse_id', $id],
                ['sale_products.is_active', true]
            ])
            ->whereNotNull('sale_product_warehouses.variant_id')
            ->get();
        
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $is_batch = [];
        $product_data = [];
        foreach ($ot_crm_product_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $product_code[] =  $product_warehouse->code;
            $product_name[] = $product_warehouse->name;
            $product_type[] = $product_warehouse->type;
            $is_batch[] = null;
        }
        //product with batches
        foreach ($ot_crm_product_with_batch_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $ot_crm_product_data = SaleProduct::select('code', 'name', 'type', 'is_batch')->find($product_warehouse->product_id);
            $product_code[] =  $ot_crm_product_data->code;
            $product_name[] = htmlspecialchars($ot_crm_product_data->name);
            $product_type[] = $ot_crm_product_data->type;
            $product_batch_data = SaleProductBatch::select('id', 'batch_no')->find($product_warehouse->product_batch_id);
            $is_batch[] = $ot_crm_product_data->is_batch;
        }

        foreach ($ot_crm_product_with_variant_warehouse_data as $product_warehouse) 
        {
            $ot_crm_product_variant_data = SaleProductVariant::select('item_code')->FindExactProduct($product_warehouse->id, $product_warehouse->variant_id)->first();
            $product_qty[] = $product_warehouse->qty;
            $product_code[] =  $ot_crm_product_variant_data->item_code;
            $product_name[] = $product_warehouse->name;
            $product_type[] = $product_warehouse->type;
            $is_batch[] = null;
        }

        $product_data = [$product_code, $product_name, $product_qty, $product_type, $is_batch];
        return $product_data;
    }

    public function ot_crmProductSearch(Request $request)
    {
        $product_code = explode("(", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $ot_crm_product_data = SaleProduct::where('code', $product_code[0])->first();
        $product_variant_id = null;
        if (!$ot_crm_product_data) {
            $ot_crm_product_data = SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
                ->select('sale_products.*', 'sale_product_variants.id as product_variant_id', 'sale_product_variants.item_code', 'sale_product_variants.additional_price')
                ->where('sale_product_variants.item_code', $product_code[0])
                ->first();
            $product_variant_id = $ot_crm_product_data->product_variant_id;
            $ot_crm_product_data->code = $ot_crm_product_data->item_code;
            $ot_crm_product_data->price += $ot_crm_product_data->additional_price;
        }

        $product[] = $ot_crm_product_data->name;
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
        $product[] = $product_variant_id;
        $product[] = $ot_crm_product_data->is_imei;
        return $product;
    }

    public function store(Request $request)
    {
        $data = $request->except('document');
        $data['reference_no'] = 'prr-' . date("Ymd") . '-'. date("his");
        $data['user_id'] = Auth::id();
        $ot_crm_purchase_data = SalePurchase::select('warehouse_id', 'supplier_id')->find($data['purchase_id']);
        $data['user_id'] = Auth::id();
        $data['supplier_id'] = $ot_crm_purchase_data->supplier_id;
        $data['warehouse_id'] = $ot_crm_purchase_data->warehouse_id;
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
            $document->move('public/return/documents', $documentName);
            $data['document'] = $documentName;
        }

        $ot_crm_return_data = SaleReturnPurchase::create($data);
        $mail_data['email'] = '';
        if($data['supplier_id']) {
            $ot_crm_supplier_data = SaleSupplier::find($data['supplier_id']);
            //collecting male data
            $mail_data['email'] = $ot_crm_supplier_data->email;
            $mail_data['reference_no'] = $ot_crm_return_data->reference_no;
            $mail_data['total_qty'] = $ot_crm_return_data->total_qty;
            $mail_data['total_price'] = $ot_crm_return_data->total_price;
            $mail_data['order_tax'] = $ot_crm_return_data->order_tax;
            $mail_data['order_tax_rate'] = $ot_crm_return_data->order_tax_rate;
            $mail_data['grand_total'] = $ot_crm_return_data->grand_total;
        }
            
        $product_id = $data['is_return'];
        $imei_number = $data['imei_number'];
        $product_batch_id = $data['product_batch_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];

        foreach ($product_id as $pro_id) {
            $key = array_search($pro_id, $data['product_id']);
            //return $key;
            $ot_crm_product_data = SaleProduct::find($pro_id);
            $variant_id = null;
            if($purchase_unit[$key] != 'n/a') {
                $ot_crm_purchase_unit_data  = SaleUnit::where('unit_name', $purchase_unit[$key])->first();
                $purchase_unit_id = $ot_crm_purchase_unit_data->id;
                if($ot_crm_purchase_unit_data->operator == '*')
                    $quantity = $qty[$key] * $ot_crm_purchase_unit_data->operation_value;
                elseif($ot_crm_purchase_unit_data->operator == '/')
                    $quantity = $qty[$key] / $ot_crm_purchase_unit_data->operation_value;

                if($ot_crm_product_data->is_variant) {
                    $ot_crm_product_variant_data = SaleProductVariant::
                        select('id', 'variant_id', 'qty')
                        ->FindExactProductWithCode($pro_id, $product_code[$key])
                        ->first();
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($pro_id, $ot_crm_product_variant_data->variant_id, $data['warehouse_id'])->first();
                    $ot_crm_product_variant_data->qty -= $quantity;
                    $ot_crm_product_variant_data->save();
                    $variant_data = SaleVariant::find($ot_crm_product_variant_data->variant_id);
                    $variant_id = $variant_data->id;
                }
                elseif($product_batch_id[$key]) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_batch_id[$key] ],
                        ['warehouse_id', $data['warehouse_id'] ]
                    ])->first();
                    $ot_crm_product_batch_data = SaleProductBatch::find($product_batch_id[$key]);
                    //increase product batch quantity
                    $ot_crm_product_batch_data->qty -= $quantity;
                    $ot_crm_product_batch_data->save();
                }
                else
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($pro_id, $data['warehouse_id'])->first();

                $ot_crm_product_data->qty -=  $quantity;
                $ot_crm_product_warehouse_data->qty -= $quantity;

                $ot_crm_product_data->save();
                $ot_crm_product_warehouse_data->save();
            }
            else {
                if($ot_crm_product_data->type == 'combo') {
                    $product_list = explode(",", $ot_crm_product_data->product_list);
                    $variant_list = explode(",", $ot_crm_product_data->variant_list);
                    $qty_list = explode(",", $ot_crm_product_data->qty_list);
                    $price_list = explode(",", $ot_crm_product_data->price_list);

                    foreach ($product_list as $index => $child_id) {
                        $child_data = SaleProduct::find($child_id);
                        if($variant_list[$index]) {
                            $child_product_variant_data = SaleProductVariant::where([
                                ['product_id', $child_id],
                                ['variant_id', $variant_list[$index]]
                            ])->first();

                            $child_warehouse_data = SaleProductWarehouse::where([
                                ['product_id', $child_id],
                                ['variant_id', $variant_list[$index]],
                                ['warehouse_id', $data['warehouse_id'] ],
                            ])->first();

                            $child_product_variant_data->qty += $qty[$key] * $qty_list[$index];
                            $child_product_variant_data->save();
                        }
                        else {
                            $child_warehouse_data = SaleProductWarehouse::where([
                                ['product_id', $child_id],
                                ['warehouse_id', $data['warehouse_id'] ],
                            ])->first();
                        }
                        
                        $child_data->qty -= $qty[$key] * $qty_list[$index];
                        $child_warehouse_data->qty -= $qty[$key] * $qty_list[$index];

                        $child_data->save();
                        $child_warehouse_data->save();
                    }
                }
                $purchase_unit_id = 0;
            }
            //add imei number if available
            if($imei_number[$key]) {
                if($ot_crm_product_warehouse_data->imei_number)
                    $ot_crm_product_warehouse_data->imei_number .= ',' . $imei_number[$key];
                 else
                    $ot_crm_product_warehouse_data->imei_number = $imei_number[$key];
                $ot_crm_product_warehouse_data->save(); 
            }
            if($ot_crm_product_data->is_variant)
                $mail_data['products'][$key] = $ot_crm_product_data->name . ' [' . $variant_data->name . ']';
            else
                $mail_data['products'][$key] = $ot_crm_product_data->name;
            
            if($purchase_unit_id)
                $mail_data['unit'][$key] = $ot_crm_purchase_unit_data->unit_code;
            else
                $mail_data['unit'][$key] = '';

            $mail_data['qty'][$key] = $qty[$key];
            $mail_data['total'][$key] = $total[$key];
            SalePurchaseProductReturn::insert(
                ['return_id' => $ot_crm_return_data->id, 'product_id' => $pro_id, 'product_batch_id' => $product_batch_id[$key], 'variant_id' => $variant_id, 'imei_number' => $imei_number[$key], 'qty' => $qty[$key], 'purchase_unit_id' => $purchase_unit_id, 'net_unit_cost' => $net_unit_cost[$key], 'discount' => $discount[$key], 'tax_rate' => $tax_rate[$key], 'tax' => $tax[$key], 'total' => $total[$key], 'created_at' => \Carbon\Carbon::now(),  'updated_at' => \Carbon\Carbon::now()]
            );
        }
        $message = 'Return created successfully';
       
        Toastr::success( $message, 'Success');
        return redirect()->route('purchaseReturn.index');
    }

    public function productReturnData($id)
    {
        $ot_crm_product_return_data = SalePurchaseProductReturn::where('return_id', $id)->get();
        foreach ($ot_crm_product_return_data as $key => $product_return_data) {
            $product = SaleProduct::find($product_return_data->product_id);
            if($product_return_data->purchase_unit_id != 0){
                $unit_data = SaleUnit::find($product_return_data->purchase_unit_id);
                $unit = $unit_data->unit_code;
            }
            else
                $unit = '';

            if($product_return_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::select('item_code')->FindExactProduct($product_return_data->product_id, $product_return_data->variant_id)->first();
                $product->code = $ot_crm_product_variant_data->item_code;
            }
            if($product_return_data->product_batch_id) {
                $product_batch_data = SaleProductBatch::select('batch_no')->find($product_return_data->product_batch_id);
                $product_return[7][$key] = $product_batch_data->batch_no;
            }
            else
                $product_return[7][$key] = 'N/A';
            $product_return[0][$key] = $product->name . ' [' . $product->code . ']';
            if($product_return_data->imei_number)
                $product_return[0][$key] .= '<br>IMEI or Serial Number: '.$product_return_data->imei_number;
            $product_return[1][$key] = $product_return_data->qty;
            $product_return[2][$key] = $unit;
            $product_return[3][$key] = $product_return_data->tax;
            $product_return[4][$key] = $product_return_data->tax_rate;
            $product_return[5][$key] = $product_return_data->discount;
            $product_return[6][$key] = $product_return_data->total;
        }
        return $product_return;
    }

    public function sendMail(Request $request)
    {
        $data = $request->all();
        $ot_crm_return_data = SaleReturnPurchase::find($data['return_id']);
        //return $ot_crm_return_data;
        $ot_crm_product_return_data = SalePurchaseProductReturn::where('return_id', $data['return_id'])->get();
        if($ot_crm_return_data->supplier_id){
            $ot_crm_supplier_data = SaleSupplier::find($ot_crm_return_data->supplier_id);
            //collecting male data
            $mail_data['email'] = $ot_crm_supplier_data->email;
            $mail_data['reference_no'] = $ot_crm_return_data->reference_no;
            $mail_data['total_qty'] = $ot_crm_return_data->total_qty;
            $mail_data['total_price'] = $ot_crm_return_data->total_cost;
            $mail_data['order_tax'] = $ot_crm_return_data->order_tax;
            $mail_data['order_tax_rate'] = $ot_crm_return_data->order_tax_rate;
            $mail_data['grand_total'] = $ot_crm_return_data->grand_total;

            foreach ($ot_crm_product_return_data as $key => $product_return_data) {
                $ot_crm_product_data = SaleProduct::find($product_return_data->product_id);
                if($product_return_data->variant_id) {
                    $variant_data = SaleVariant::find($product_return_data->variant_id);
                    $mail_data['products'][$key] = $ot_crm_product_data->name . ' [' . $variant_data->name . ']';
                }
                else
                    $mail_data['products'][$key] = $ot_crm_product_data->name;

                if($product_return_data->purchase_unit_id){
                    $ot_crm_unit_data = SaleUnit::find($product_return_data->purchase_unit_id);
                    $mail_data['unit'][$key] = $ot_crm_unit_data->unit_code;
                }
                else
                    $mail_data['unit'][$key] = '';

                $mail_data['qty'][$key] = $product_return_data->qty;
                $mail_data['total'][$key] = $product_return_data->qty;
            }
            try{
                Mail::send( 'mail.return_details', $mail_data, function( $message ) use ($mail_data)
                {
                    $message->to( $mail_data['email'] )->subject( 'Return Details' );
                });
                $message = 'Mail sent successfully';
            }
            catch(\Exception $e){
                $message = 'Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        else
            $message = "This return doesn't belong to any supplier";
        
        return redirect()->back()->with('message', $message);
    }

    public function edit($id)
    {
        $ot_crm_supplier_list = SaleSupplier::where('is_active',true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active',true)->get();
        $ot_crm_account_list = SaleAccount::where('is_active',true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active',true)->get();
        $ot_crm_return_data = SaleReturnPurchase::find($id);
        $ot_crm_product_return_data = SalePurchaseProductReturn::where('return_id', $id)->get();
        $data['title'] = 'Purchase Return Edit';
        return view('sale::return.purchase.edit',compact('data','ot_crm_supplier_list', 'ot_crm_warehouse_list', 'ot_crm_tax_list', 'ot_crm_account_list', 'ot_crm_return_data','ot_crm_product_return_data'));
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
            $document->move('public/return/documents', $documentName);
            $data['document'] = $documentName;
        }

        $ot_crm_return_data = SaleReturnPurchase::find($id);
        $ot_crm_product_return_data = SalePurchaseProductReturn::where('return_id', $id)->get();

        $product_id = $data['product_id'];
        $imei_number = $data['imei_number'];
        $product_batch_id = $data['product_batch_id'];
        $product_code = $data['product_code'];
        $product_variant_id = $data['product_variant_id'];
        $qty = $data['qty'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];

        foreach ($ot_crm_product_return_data as $key => $product_return_data) {
            $old_product_id[] = $product_return_data->product_id;
            $old_product_variant_id[] = null;
            $ot_crm_product_data = SaleProduct::find($product_return_data->product_id);
            if($product_return_data->purchase_unit_id != 0) {
                $ot_crm_purchase_unit_data = SaleUnit::find($product_return_data->purchase_unit_id);
                if ($ot_crm_purchase_unit_data->operator == '*')
                    $quantity = $product_return_data->qty * $ot_crm_purchase_unit_data->operation_value;
                elseif($ot_crm_purchase_unit_data->operator == '/')
                    $quantity = $product_return_data->qty / $ot_crm_purchase_unit_data->operation_value;

                if($product_return_data->variant_id) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($product_return_data->product_id, $product_return_data->variant_id)->first();
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_return_data->product_id, $product_return_data->variant_id, $ot_crm_return_data->warehouse_id)
                    ->first();
                    $old_product_variant_id[$key] = $ot_crm_product_variant_data->id;
                    $ot_crm_product_variant_data->qty += $quantity;
                    $ot_crm_product_variant_data->save();
                }
                elseif($product_return_data->product_batch_id) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $product_return_data->product_id],
                        ['product_batch_id', $product_return_data->product_batch_id],
                        ['warehouse_id', $ot_crm_return_data->warehouse_id]
                    ])->first();

                    $product_batch_data = SaleProductBatch::find($product_return_data->product_batch_id);
                    $product_batch_data->qty += $quantity;
                    $product_batch_data->save();
                }
                else
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_return_data->product_id, $ot_crm_return_data->warehouse_id)
                    ->first();

                if($product_return_data->imei_number) {
                    if($ot_crm_product_warehouse_data->imei_number)
                        $ot_crm_product_warehouse_data->imei_number .= ','.$product_return_data->imei_number;
                    else
                        $ot_crm_product_warehouse_data->imei_number = $product_return_data->imei_number;
                }

                $ot_crm_product_data->qty += $quantity;
                $ot_crm_product_warehouse_data->qty += $quantity;
                $ot_crm_product_data->save();
                $ot_crm_product_warehouse_data->save();
            }
            if($product_return_data->variant_id && !(in_array($old_product_variant_id[$key], $product_variant_id)) ){
                $product_return_data->delete();
            }
            elseif( !(in_array($old_product_id[$key], $product_id)) )
                $product_return_data->delete();
        }
        foreach ($product_id as $key => $pro_id) {
            $ot_crm_product_data = SaleProduct::find($pro_id);
            $product_return['variant_id'] = null;
            if($purchase_unit[$key] != 'n/a'){
                $ot_crm_purchase_unit_data = SaleUnit::where('unit_name', $purchase_unit[$key])->first();
                $purchase_unit_id = $ot_crm_purchase_unit_data->id;
                if ($ot_crm_purchase_unit_data->operator == '*')
                    $quantity = $qty[$key] * $ot_crm_purchase_unit_data->operation_value;
                elseif($ot_crm_purchase_unit_data->operator == '/')
                    $quantity = $qty[$key] / $ot_crm_purchase_unit_data->operation_value;

                if($ot_crm_product_data->is_variant) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($pro_id, $ot_crm_product_variant_data->variant_id, $data['warehouse_id'])
                    ->first();
                    $variant_data = SaleVariant::find($ot_crm_product_variant_data->variant_id);

                    $product_return['variant_id'] = $ot_crm_product_variant_data->variant_id;
                    $ot_crm_product_variant_data->qty -= $quantity;
                    $ot_crm_product_variant_data->save();
                }
                elseif($product_batch_id[$key]) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $pro_id],
                        ['product_batch_id', $product_batch_id[$key] ],
                        ['warehouse_id', $data['warehouse_id'] ]
                    ])->first();

                    $product_batch_data = SaleProductBatch::find($product_batch_id[$key]);
                    $product_batch_data->qty -= $quantity;
                    $product_batch_data->save();
                }
                else {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($pro_id, $data['warehouse_id'])
                    ->first();
                }
                //deduct imei number if available
                if($imei_number[$key]) {
                    $imei_numbers = explode(",", $imei_number[$key]);
                    $all_imei_numbers = explode(",", $ot_crm_product_warehouse_data->imei_number);
                    foreach ($imei_numbers as $number) {
                        if (($j = array_search($number, $all_imei_numbers)) !== false) {
                            unset($all_imei_numbers[$j]);
                        }
                    }
                    $ot_crm_product_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                }

                $ot_crm_product_data->qty -=  $quantity;
                $ot_crm_product_warehouse_data->qty -= $quantity;

                $ot_crm_product_data->save();
                $ot_crm_product_warehouse_data->save();
            }

            if($ot_crm_product_data->is_variant)
                $mail_data['products'][$key] = $ot_crm_product_data->name . ' [' . $variant_data->name .']';
            else
                $mail_data['products'][$key] = $ot_crm_product_data->name;

            if($purchase_unit_id)
                $mail_data['unit'][$key] = $ot_crm_purchase_unit_data->unit_code;
            else
                $mail_data['unit'][$key] = '';

            $mail_data['qty'][$key] = $qty[$key];
            $mail_data['total'][$key] = $total[$key];

            $product_return['return_id'] = $id ;
            $product_return['product_id'] = $pro_id;
            $product_return['imei_number'] = $imei_number[$key];
            $product_return['product_batch_id'] = $product_batch_id[$key];
            $product_return['qty'] = $qty[$key];
            $product_return['purchase_unit_id'] = $purchase_unit_id;
            $product_return['net_unit_cost'] = $net_unit_cost[$key];
            $product_return['discount'] = $discount[$key];
            $product_return['tax_rate'] = $tax_rate[$key];
            $product_return['tax'] = $tax[$key];
            $product_return['total'] = $total[$key];

            if($product_return['variant_id'] && in_array($product_variant_id[$key], $old_product_variant_id)) {
                SalePurchaseProductReturn::where([
                    ['product_id', $pro_id],
                    ['variant_id', $product_return['variant_id']],
                    ['return_id', $id]
                ])->update($product_return);
            }
            elseif( $product_return['variant_id'] === null && (in_array($pro_id, $old_product_id)) ) {
                SalePurchaseProductReturn::where([
                    ['return_id', $id],
                    ['product_id', $pro_id]
                    ])->update($product_return);
            }
            else
                SalePurchaseProductReturn::create($product_return);
        }
        $ot_crm_return_data->update($data);
        $message = 'Return updated successfully';
        if($data['supplier_id']) {
            $ot_crm_supplier_data = SaleSupplier::find($data['supplier_id']);
            //collecting male data
            $mail_data['email'] = $ot_crm_supplier_data->email;
            $mail_data['reference_no'] = $ot_crm_return_data->reference_no;
            $mail_data['total_qty'] = $ot_crm_return_data->total_qty;
            $mail_data['total_price'] = $ot_crm_return_data->total_cost;
            $mail_data['order_tax'] = $ot_crm_return_data->order_tax;
            $mail_data['order_tax_rate'] = $ot_crm_return_data->order_tax_rate;
            $mail_data['grand_total'] = $ot_crm_return_data->grand_total;
             
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('purchaseReturn.index');
    }

    public function deleteBySelection(Request $request)
    {
        $return_id = $request['returnIdArray'];
        foreach ($return_id as $id) {
            $ot_crm_return_data = SaleReturnPurchase::find($id);
            $ot_crm_product_return_data = SalePurchaseProductReturn::where('return_id', $id)->get();

            foreach ($ot_crm_product_return_data as $key => $product_return_data) {
                $ot_crm_product_data = SaleProduct::find($product_return_data->product_id);

                if($product_return_data->purchase_unit_id != 0){
                    $ot_crm_purchase_unit_data = SaleUnit::find($product_return_data->purchase_unit_id);

                    if ($ot_crm_purchase_unit_data->operator == '*')
                        $quantity = $product_return_data->qty * $ot_crm_purchase_unit_data->operation_value;
                    elseif($ot_crm_purchase_unit_data->operator == '/')
                        $quantity = $product_return_data->qty / $ot_crm_purchase_unit_data->operation_value;

                    if($product_return_data->variant_id) {
                        $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($product_return_data->product_id, $product_return_data->variant_id)->first();
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_return_data->product_id, $product_return_data->variant_id, $ot_crm_return_data->warehouse_id)->first();
                        $ot_crm_product_variant_data->qty += $quantity;
                        $ot_crm_product_variant_data->save();
                    }
                    elseif($product_return_data->product_batch_id) {
                        $ot_crm_product_batch_data = SaleProductBatch::find($product_return_data->product_batch_id);
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                            ['product_batch_id', $product_return_data->product_batch_id],
                            ['warehouse_id', $ot_crm_return_data->warehouse_id]
                        ])->first();

                        $ot_crm_product_batch_data->qty += $product_return_data->qty;
                        $ot_crm_product_batch_data->save();
                    }
                    else
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_return_data->product_id, $ot_crm_return_data->warehouse_id)->first();

                    $ot_crm_product_data->qty += $quantity;
                    $ot_crm_product_warehouse_data->qty += $quantity;
                    $ot_crm_product_data->save();
                    $ot_crm_product_warehouse_data->save();
                    $product_return_data->delete();
                }
            }
            $ot_crm_return_data->delete();
        }
        return 'Return deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_return_data = SaleReturnPurchase::find($id);
        $ot_crm_product_return_data = SalePurchaseProductReturn::where('return_id', $id)->get();

        foreach ($ot_crm_product_return_data as $key => $product_return_data) {
            $ot_crm_product_data = SaleProduct::find($product_return_data->product_id);

            if($product_return_data->purchase_unit_id != 0) {
                $ot_crm_purchase_unit_data = SaleUnit::find($product_return_data->purchase_unit_id);

                if ($ot_crm_purchase_unit_data->operator == '*')
                    $quantity = $product_return_data->qty * $ot_crm_purchase_unit_data->operation_value;
                elseif($ot_crm_purchase_unit_data->operator == '/')
                    $quantity = $product_return_data->qty / $ot_crm_purchase_unit_data->operation_value;

                if($product_return_data->variant_id) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($product_return_data->product_id, $product_return_data->variant_id)->first();
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_return_data->product_id, $product_return_data->variant_id, $ot_crm_return_data->warehouse_id)->first();
                    $ot_crm_product_variant_data->qty += $quantity;
                    $ot_crm_product_variant_data->save();
                }
                elseif($product_return_data->product_batch_id) {
                    $ot_crm_product_batch_data = SaleProductBatch::find($product_return_data->product_batch_id);
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_return_data->product_batch_id],
                        ['warehouse_id', $ot_crm_return_data->warehouse_id]
                    ])->first();

                    $ot_crm_product_batch_data->qty += $product_return_data->qty;
                    $ot_crm_product_batch_data->save();
                }
                else
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_return_data->product_id, $ot_crm_return_data->warehouse_id)->first();
                
                if($product_return_data->imei_number) {
                    if($ot_crm_product_warehouse_data->imei_number)
                        $ot_crm_product_warehouse_data->imei_number .= ','.$product_return_data->imei_number;
                    else
                        $ot_crm_product_warehouse_data->imei_number = $product_return_data->imei_number;
                }

                $ot_crm_product_data->qty += $quantity;
                $ot_crm_product_warehouse_data->qty += $quantity;
                $ot_crm_product_data->save();
                $ot_crm_product_warehouse_data->save();
                $product_return_data->delete();
            }
        }
        $ot_crm_return_data->delete();
        Toastr::success(_trans('response.Purchase return deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
