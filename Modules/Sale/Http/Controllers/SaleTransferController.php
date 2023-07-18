<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\SaleTax;
use Modules\Sale\Entities\SaleUnit;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleTransfer;
use Modules\Sale\Entities\SaleWarehouse;
use Modules\Sale\Entities\SaleProductBatch;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleProductVariant;
use Modules\Sale\Entities\SaleProductTransfer;
use Modules\Sale\Entities\SaleProductWarehouse;

class SaleTransferController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
    
        $data['title'] = 'Transfer List';
        $data['warehouses'] = SaleWarehouse::where('is_active', true)->get();
        $data['search'] = '';
        $data['transfers'] = SaleTransfer::with('fromWarehouse','user','toWarehouse');
        if ($search != "") {
            $data['transfers'] = $data['transfers']->where('reference_no', 'like', '%' . $search . '%');
        }
        $data['transfers'] = $data['transfers']->latest()->paginate($entries);

        return view('sale::transfer.index',compact( 'data'));
    }
    public function create()
    {
        $data['title'] = 'Transfer Create';
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        return view('sale::transfer.create', compact('data','ot_crm_warehouse_list'));
    }

    public function getProduct($id)
    {
        $ot_crm_product_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
        ->where([
            ['sale_products.is_active', true],
            ['sale_product_warehouses.warehouse_id', $id],
            ['sale_product_warehouses.qty', '>', 0]
        ])
        ->whereNull('sale_product_warehouses.variant_id')
        ->whereNull('sale_product_warehouses.product_batch_id')
        ->select('sale_product_warehouses.*')
        ->get();

        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect(); //important as the existing connection if any would be in strict mode

        $ot_crm_product_with_batch_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
        ->where([
            ['sale_products.is_active', true],
            ['sale_product_warehouses.warehouse_id', $id],
            ['sale_product_warehouses.qty', '>', 0]
        ])
        ->whereNull('sale_product_warehouses.variant_id')
        ->whereNotNull('sale_product_warehouses.product_batch_id')
        ->select('sale_product_warehouses.*')
        ->groupBy('sale_product_warehouses.product_id')
        ->get();

        //now changing back the strict ON
        config()->set('database.connections.mysql.strict', true);
        \DB::reconnect();

        $ot_crm_product_with_variant_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
        ->where([
            ['sale_products.is_active', true],
            ['sale_product_warehouses.warehouse_id', $id],
            ['sale_product_warehouses.qty', '>', 0]
        ])->whereNotNull('sale_product_warehouses.variant_id')->select('sale_product_warehouses.*')->get();

        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_data = [];
        //product without variant
        foreach ($ot_crm_product_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $ot_crm_product_data = SaleProduct::select('name', 'code')->find($product_warehouse->product_id);
            $product_code[] =  $ot_crm_product_data->code;
            $product_name[] = $ot_crm_product_data->name;
        }
        //product without batch
        foreach ($ot_crm_product_with_batch_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $ot_crm_product_data = SaleProduct::select('name', 'code')->find($product_warehouse->product_id);
            $product_code[] =  $ot_crm_product_data->code;
            $product_name[] = $ot_crm_product_data->name;
        }
        //product with variant
        foreach ($ot_crm_product_with_variant_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $ot_crm_product_data = SaleProduct::select('name', 'code')->find($product_warehouse->product_id);
            $ot_crm_product_variant_data = SaleProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
            $product_code[] =  $ot_crm_product_variant_data->item_code;
            $product_name[] = $ot_crm_product_data->name;
        }
        $product_data = [$product_code, $product_name, $product_qty];
        return $product_data;
    }

    public function ot_crmProductSearch(Request $request)
    {
        $product_code = explode("(", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $product_variant_id = null;
        $ot_crm_product_data = SaleProduct::where([
            ['code', $product_code[0]],
            ['is_active', true]
        ])->first();
        if(!$ot_crm_product_data) {
            $ot_crm_product_data = SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
                ->select('sale_products.*', 'sale_product_variants.id as product_variant_id', 'sale_product_variants.item_code', 'sale_product_variants.additional_cost')
                ->where('sale_product_variants.item_code', $product_code[0])
                ->first();
            $product_variant_id = $ot_crm_product_data->product_variant_id;
            $ot_crm_product_data->code = $ot_crm_product_data->item_code;
            $ot_crm_product_data->cost += $ot_crm_product_data->additional_cost;
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
        $product[] = $ot_crm_product_data->is_batch;
        $product[] = $ot_crm_product_data->is_imei;
        return $product;
    }

    public function store(Request $request)
    {
        $data = $request->except('document');
        $data['user_id'] = Auth::id();
        $data['reference_no'] = 'tr-' . date("Ymd") . '-'. date("his");
        if(isset($data['created_at']))
            $data['created_at'] = date("Y-m-d H:i:s", strtotime($data['created_at']));
        else
            $data['created_at'] = date("Y-m-d H:i:s");
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
            $document->move('public/documents/transfer', $documentName);
            $data['document'] = $documentName;
        }
        $ot_crm_transfer_data = SaleTransfer::create($data);

        $product_id = $data['product_id'];
        $imei_number = $data['imei_number'];
        $product_batch_id = $data['product_batch_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $product_transfer = [];

        foreach ($product_id as $i => $id) {
            $ot_crm_purchase_unit_data  = SaleUnit::where('unit_name', $purchase_unit[$i])->first();
            $product_transfer['variant_id'] = null;
            $product_transfer['product_batch_id'] = null;
            
            //get product data
            $ot_crm_product_data = SaleProduct::select('is_variant')->find($id);
            if($ot_crm_product_data->is_variant) {
                $ot_crm_product_variant_data = SaleProductVariant::select('variant_id')->FindExactProductWithCode($id, $product_code[$i])->first();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($id, $ot_crm_product_variant_data->variant_id, $data['from_warehouse_id'])->first();
                $product_transfer['variant_id'] = $ot_crm_product_variant_data->variant_id;
            }
            elseif($product_batch_id[$i]) {
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_batch_id', $product_batch_id[$i] ],
                    ['warehouse_id', $data['from_warehouse_id'] ]
                ])->first();
                $product_transfer['product_batch_id'] = $product_batch_id[$i];
            }
            else {
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $id],
                    ['warehouse_id', $data['from_warehouse_id'] ],
                    ])->first();
            }

            if($data['status'] != 2) {
                if ($ot_crm_purchase_unit_data->operator == '*')
                    $quantity = $qty[$i] * $ot_crm_purchase_unit_data->operation_value;
                else 
                    $quantity = $qty[$i] / $ot_crm_purchase_unit_data->operation_value;
                //deduct imei number if available
                if($imei_number[$i]) {
                    $imei_numbers = explode(",", $imei_number[$i]);
                    $all_imei_numbers = explode(",", $ot_crm_product_warehouse_data->imei_number);
                    foreach ($imei_numbers as $number) {
                        if (($j = array_search($number, $all_imei_numbers)) !== false) {
                            unset($all_imei_numbers[$j]);
                        }
                    }
                    $ot_crm_product_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                }
            }
            else
                $quantity = 0;
            //deduct quantity from sending warehouse
            $ot_crm_product_warehouse_data->qty -= $quantity;
            $ot_crm_product_warehouse_data->save();
            
            if($data['status'] == 1) {
                if($ot_crm_product_data->is_variant) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($id, $ot_crm_product_variant_data->variant_id, $data['to_warehouse_id'])->first();
                }
                elseif($product_batch_id[$i]) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_batch_id[$i] ],
                        ['warehouse_id', $data['to_warehouse_id'] ]
                    ])->first();
                }
                else {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $id],
                        ['warehouse_id', $data['to_warehouse_id'] ],
                    ])->first();
                }
                //add quantity to destination warehouse
                if ($ot_crm_product_warehouse_data)
                    $ot_crm_product_warehouse_data->qty += $quantity;
                else {
                    $ot_crm_product_warehouse_data = new SaleProductWarehouse();
                    $ot_crm_product_warehouse_data->product_id = $id;
                    $ot_crm_product_warehouse_data->product_batch_id = $product_transfer['product_batch_id'];
                    $ot_crm_product_warehouse_data->variant_id = $product_transfer['variant_id'];
                    $ot_crm_product_warehouse_data->warehouse_id = $data['to_warehouse_id'];
                    $ot_crm_product_warehouse_data->qty = $quantity;
                }
                //add imei number if available
                if($imei_number[$i]) {
                    if($ot_crm_product_warehouse_data->imei_number)
                        $ot_crm_product_warehouse_data->imei_number .= ',' . $imei_number[$i];
                    else
                        $ot_crm_product_warehouse_data->imei_number = $imei_number[$i];
                }

                $ot_crm_product_warehouse_data->save();
            }

            $product_transfer['transfer_id'] = $ot_crm_transfer_data->id ;
            $product_transfer['product_id'] = $id;
            $product_transfer['imei_number'] = $imei_number[$i];
            $product_transfer['qty'] = $qty[$i];
            $product_transfer['purchase_unit_id'] = $ot_crm_purchase_unit_data->id;
            $product_transfer['net_unit_cost'] = $net_unit_cost[$i];
            $product_transfer['tax_rate'] = $tax_rate[$i];
            $product_transfer['tax'] = $tax[$i];
            $product_transfer['total'] = $total[$i];
            SaleProductTransfer::create($product_transfer);
        }

        Toastr::success( 'Transfer created successfully', 'Success');
        return redirect()->route('saleTransfer.index');
    }

    public function productTransferData($id)
    {
        $ot_crm_product_transfer_data = SaleProductTransfer::where('transfer_id', $id)->get();
        foreach ($ot_crm_product_transfer_data as $key => $product_transfer_data) {
            $product = SaleProduct::find($product_transfer_data->product_id);
            $unit = SaleUnit::find($product_transfer_data->purchase_unit_id);
            if($product_transfer_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::select('item_code')->FindExactProduct($product_transfer_data->product_id, $product_transfer_data->variant_id)->first();
                $product->code = $ot_crm_product_variant_data->item_code;
            }
            $product_transfer[0][$key] = $product->name . ' [' . $product->code. ']';
            if($product_transfer_data->imei_number)
                $product_transfer[0][$key] .= '<br>IMEI or Serial Number: ' . $product_transfer_data->imei_number;
            $product_transfer[1][$key] = $product_transfer_data->qty;
            $product_transfer[2][$key] = $unit->unit_code;
            $product_transfer[3][$key] = $product_transfer_data->tax;
            $product_transfer[4][$key] = $product_transfer_data->tax_rate;
            $product_transfer[5][$key] = $product_transfer_data->total;
            if($product_transfer_data->product_batch_id) {
                $product_batch_data = SaleProductBatch::select('batch_no')->find($product_transfer_data->product_batch_id);
                $product_transfer[6][$key] = $product_batch_data->batch_no;
            }
            else
                $product_transfer[6][$key] = 'N/A';
            
        }
        $data['product_transfer'] = $product_transfer;
        $transfer = SaleTransfer::find($id);

        $data['transfer'] = [
            'id' => $transfer->id,
            'reference_no' => $transfer->reference_no,
            'from' => $transfer->fromWarehouse->name,
            'from_phone' => $transfer->fromWarehouse->phone,
            'from_address' => $transfer->fromWarehouse->address,
            'to' => $transfer->toWarehouse->name,
            'to_phone' => $transfer->toWarehouse->phone,
            'to_address' => $transfer->toWarehouse->address,
            'date' => date("Y-m-d", strtotime(@$transfer->created_at->toDateString())),
            'status' => $transfer->status,
            'note' => $transfer->note,
            'created_by' => $transfer->user->name,
            'total_tax' => $transfer->total_tax,
            'total_cost' => $transfer->total_cost,
            'grand_total' => $transfer->grand_total,
            'shipping_cost' => $transfer->shipping_cost,
        ];
        
        // $data['transfer'] = $transfer;
        return $data;
    }

    public function transferByCsv()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('transfers-add')){
            $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
            return view('backend.transfer.import', compact('ot_crm_warehouse_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function importTransfer(Request $request)
    {
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
                    return redirect()->back()->with('message', 'Product does not exist!');
                $unit[] = SaleUnit::where('unit_code', $current_line[2])->first();
                if(!$unit[$i-1])
                    return redirect()->back()->with('message', 'Purchase unit does not exist!');
                if(strtolower($current_line[4]) != "no tax"){
                    $tax[] = SaleTax::where('name', $current_line[4])->first();
                    if(!$tax[$i-1])
                        return redirect()->back()->with('message', 'Tax name does not exist!');
                }
                else
                    $tax[$i-1]['rate'] = 0;

                $qty[] = $current_line[1];
                $cost[] = $current_line[3];
            }
            $i++;
        }

        $data = $request->except('file');
        $data['reference_no'] = 'tr-' . date("Ymd") . '-'. date("his");
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
            $document->move('public/documents/transfer', $documentName);
            $data['document'] = $documentName;
        }
        $item = 0;
        $grand_total = $data['shipping_cost'];
        $data['user_id'] = Auth::id();
        SaleTransfer::create($data);
        $ot_crm_transfer_data = SaleTransfer::latest()->first();
        
        foreach ($product_data as $key => $product) {
            if($product['tax_method'] == 1){
                $net_unit_cost = $cost[$key];
                $product_tax = $net_unit_cost * ($tax[$key]['rate'] / 100) * $qty[$key];
                $total = ($net_unit_cost * $qty[$key]) + $product_tax;
            }
            elseif($product['tax_method'] == 2){
                $net_unit_cost = (100 / (100 + $tax[$key]['rate'])) * $cost[$key];
                $product_tax = ($cost[$key] - $net_unit_cost) * $qty[$key];
                $total = $cost[$key] * $qty[$key];
            }
            if($data['status'] == 1){
                if($unit[$key]['operator'] == '*')
                    $quantity = $qty[$key] * $unit[$key]['operation_value'];
                elseif($unit[$key]['operator'] == '/')
                    $quantity = $qty[$key] / $unit[$key]['operation_value'];
                $product_warehouse = SaleProductWarehouse::where([
                    ['product_id', $product['id']],
                    ['warehouse_id', $data['from_warehouse_id']]
                ])->first();
                $product_warehouse->qty -= $quantity;
                $product_warehouse->save();
                $product_warehouse = SaleProductWarehouse::where([
                    ['product_id', $product['id']],
                    ['warehouse_id', $data['to_warehouse_id']]
                ])->first();
                if($product_warehouse) {
                    $product_warehouse->qty += $quantity;
                    $product_warehouse->save();
                }
                else {
                    $product_warehouse = new SaleProductWarehouse();
                    $product_warehouse->product_id = $product['id'];
                    $product_warehouse->warehouse_id = $data['to_warehouse_id'];
                    $product_warehouse->qty = $quantity;
                    $product_warehouse->save();
                }
            }
            elseif ($data['status'] == 3) {
                if($unit[$key]['operator'] == '*')
                    $quantity = $qty[$key] * $unit[$key]['operation_value'];
                elseif($unit[$key]['operator'] == '/')
                    $quantity = $qty[$key] / $unit[$key]['operation_value'];
                $product_warehouse = SaleProductWarehouse::where([
                    ['product_id', $product['id']],
                    ['warehouse_id', $data['from_warehouse_id']]
                ])->first();
                $product_warehouse->qty -= $quantity;
                $product_warehouse->save();
            }
            
            $product_transfer = new ProductTransfer();
            $product_transfer->transfer_id = $ot_crm_transfer_data->id;
            $product_transfer->product_id = $product['id'];
            $product_transfer->qty = $qty[$key];
            $product_transfer->purchase_unit_id = $unit[$key]['id'];
            $product_transfer->net_unit_cost = number_format((float)$net_unit_cost, 2, '.', '');
            $product_transfer->tax_rate = $tax[$key]['rate'];
            $product_transfer->tax = number_format((float)$product_tax, 2, '.', '');
            $product_transfer->total = number_format((float)$total, 2, '.', '');
            $product_transfer->save();
            $ot_crm_transfer_data->total_qty += $qty[$key];
            $ot_crm_transfer_data->total_tax += number_format((float)$product_tax, 2, '.', '');
            $ot_crm_transfer_data->total_cost += number_format((float)$total, 2, '.', '');
        }
        $ot_crm_transfer_data->item = $key + 1;
        $ot_crm_transfer_data->grand_total = $ot_crm_transfer_data->total_cost + $ot_crm_transfer_data->shipping_cost;
        Toastr::success( 'Transfer Imported successfully', 'Success');
        return redirect()->route('saleTransfer.index');
    }

    public function edit($id)
    {
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active',true)->get();
        $ot_crm_transfer_data = SaleTransfer::find($id);
        $ot_crm_product_transfer_data = SaleProductTransfer::where('transfer_id', $id)->get();
        $data['title'] = 'Transfer Create';
        return view('sale::transfer.edit', compact('data','ot_crm_warehouse_list', 'ot_crm_transfer_data', 'ot_crm_product_transfer_data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        $document = $request->document;
        $data['created_at'] = date("Y-m-d", strtotime(str_replace("/", "-", $data['created_at'])));
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
            $document->move('public/documents/transfer', $documentName);
            $data['document'] = $documentName;
        }

        $ot_crm_transfer_data = SaleTransfer::find($id);
        $ot_crm_product_transfer_data = SaleProductTransfer::where('transfer_id', $id)->get();
        $product_id = $data['product_id'];
        $imei_number = $data['imei_number'];
        $product_batch_id = $data['product_batch_id'];
        $product_variant_id = $data['product_variant_id'];
        $qty = $data['qty'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $product_transfer = [];
        foreach ($ot_crm_product_transfer_data as $key => $product_transfer_data) {
            $old_product_id[] = $product_transfer_data->product_id;
            $old_product_variant_id[] = null;
            $ot_crm_transfer_unit_data = SaleUnit::find($product_transfer_data->purchase_unit_id);
            if ($ot_crm_transfer_unit_data->operator == '*') {
                $quantity = $product_transfer_data->qty * $ot_crm_transfer_unit_data->operation_value;
            } else {
                $quantity = $product_transfer_data->qty / $ot_crm_transfer_unit_data->operation_value;
            }
            
            if($ot_crm_transfer_data->status == 1){
                if($product_transfer_data->variant_id) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id')->FindExactProduct($product_transfer_data->product_id, $product_transfer_data->variant_id)->first();
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                    $ot_crm_product_to_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->to_warehouse_id)->first();
                    $old_product_variant_id[$key] = $ot_crm_product_variant_data->id;
                }
                elseif($product_transfer_data->product_batch_id) {
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_transfer_data->product_batch_id ],
                        ['warehouse_id', $ot_crm_transfer_data->from_warehouse_id ]
                    ])->first();

                    $ot_crm_product_to_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_transfer_data->product_batch_id ],
                        ['warehouse_id', $ot_crm_transfer_data->to_warehouse_id ]
                    ])->first();
                }
                else {
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                    $ot_crm_product_to_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->to_warehouse_id)->first();
                }
                
                if($product_transfer_data->imei_number) {
                    //add imei number to from warehouse
                    if($ot_crm_product_from_warehouse_data->imei_number)
                        $ot_crm_product_from_warehouse_data->imei_number .= ',' . $product_transfer_data->imei_number;
                    else
                        $ot_crm_product_from_warehouse_data->imei_number = $product_transfer_data->imei_number;
                    //deduct imei number from to warehouse
                    $imei_numbers = explode(",", $product_transfer_data->imei_number);
                    $all_imei_numbers = explode(",", $ot_crm_product_to_warehouse_data->imei_number);
                    foreach ($imei_numbers as $number) {
                        if (($j = array_search($number, $all_imei_numbers)) !== false) {
                            unset($all_imei_numbers[$j]);
                        }
                    }
                    $ot_crm_product_to_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                }
                    
                $ot_crm_product_from_warehouse_data->qty += $quantity;
                $ot_crm_product_from_warehouse_data->save();

                $ot_crm_product_to_warehouse_data->qty -= $quantity;
                $ot_crm_product_to_warehouse_data->save();
            }
            elseif($ot_crm_transfer_data->status == 3) {
                if($product_transfer_data->variant_id) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id')->FindExactProduct($product_transfer_data->product_id, $product_transfer_data->variant_id)->first();
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                    $old_product_variant_id[$key] = $ot_crm_product_variant_data->id;
                }
                elseif($product_transfer_data->product_batch_id) {
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_transfer_data->product_batch_id ],
                        ['warehouse_id', $ot_crm_transfer_data->from_warehouse_id ]
                    ])->first();
                }
                else {
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                }
                if($product_transfer_data->imei_number) {
                    //add imei number to from warehouse
                    if($ot_crm_product_from_warehouse_data->imei_number)
                        $ot_crm_product_from_warehouse_data->imei_number .= ',' . $product_transfer_data->imei_number;
                    else
                        $ot_crm_product_from_warehouse_data->imei_number = $product_transfer_data->imei_number;
                }
                $ot_crm_product_from_warehouse_data->qty += $quantity;
                $ot_crm_product_from_warehouse_data->save();
            }
            
            if($product_transfer_data->variant_id && !(in_array($old_product_variant_id[$key], $product_variant_id)) ){
                $product_transfer_data->delete();
            }
            elseif( !(in_array($old_product_id[$key], $product_id)) ){
                $product_transfer_data->delete();
            }
        }

        foreach ($product_id as $key => $pro_id) {
            $ot_crm_product_data = SaleProduct::select('is_variant')->find($pro_id);
            $ot_crm_transfer_unit_data = SaleUnit::where('unit_name', $purchase_unit[$key])->first();
            $variant_id = null;
            $product_transfer['product_batch_id'] = null;
            //unit conversion
            if ($ot_crm_transfer_unit_data->operator == '*') {
                $quantity = $qty[$key] * $ot_crm_transfer_unit_data->operation_value;
            } else {
                $quantity = $qty[$key] / $ot_crm_transfer_unit_data->operation_value;
            }

            if($data['status'] == 1) {
                if($ot_crm_product_data->is_variant) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('variant_id')->find($product_variant_id[$key]);
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::FindProductWithVariant($pro_id, $ot_crm_product_variant_data->variant_id, $data['from_warehouse_id'])->first();
                    $ot_crm_product_to_warehouse_data = SaleProductWarehouse::FindProductWithVariant($pro_id, $ot_crm_product_variant_data->variant_id, $data['to_warehouse_id'])->first();
                    $variant_id = $ot_crm_product_variant_data->variant_id;
                }
                elseif($product_batch_id[$key]) {
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_batch_id[$key] ],
                        ['warehouse_id', $data['from_warehouse_id'] ]
                    ])->first();

                    $ot_crm_product_to_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_batch_id[$key] ],
                        ['warehouse_id', $data['to_warehouse_id'] ]
                    ])->first();
                    $product_transfer['product_batch_id'] = $product_batch_id[$key];
                }
                else{
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($pro_id, $data['from_warehouse_id'])->first();
                    $ot_crm_product_to_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($pro_id, $data['to_warehouse_id'])->first();
                }
                //deduct imei number if available
                if($imei_number[$key]) {
                    $imei_numbers = explode(",", $imei_number[$key]);
                    $all_imei_numbers = explode(",", $ot_crm_product_from_warehouse_data->imei_number);
                    foreach ($imei_numbers as $number) {
                        if (($j = array_search($number, $all_imei_numbers)) !== false) {
                            unset($all_imei_numbers[$j]);
                        }
                    }
                    $ot_crm_product_from_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                }

                $ot_crm_product_from_warehouse_data->qty -= $quantity;
                $ot_crm_product_from_warehouse_data->save();

                if($ot_crm_product_to_warehouse_data){
                    $ot_crm_product_to_warehouse_data->qty += $quantity;
                }
                else{
                    $ot_crm_product_to_warehouse_data = new SaleProductWarehouse();
                    $ot_crm_product_to_warehouse_data->product_id = $pro_id;
                    $ot_crm_product_to_warehouse_data->variant_id = $variant_id;
                    $ot_crm_product_to_warehouse_data->product_batch_id = $product_transfer['product_batch_id'];
                    $ot_crm_product_to_warehouse_data->warehouse_id = $data['to_warehouse_id'];
                    $ot_crm_product_to_warehouse_data->qty = $quantity;
                }
                //add imei number if available
                if($imei_number[$key]) {
                    if($ot_crm_product_to_warehouse_data->imei_number)
                        $ot_crm_product_to_warehouse_data->imei_number .= ',' . $imei_number[$key];
                    else
                        $ot_crm_product_to_warehouse_data->imei_number = $imei_number[$key];
                }
                $ot_crm_product_to_warehouse_data->save();
            }
            elseif($data['status'] == 3) {
                if($ot_crm_product_data->is_variant) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('variant_id')->find($product_variant_id[$key]);
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::FindProductWithVariant($pro_id, $ot_crm_product_variant_data->variant_id, $data['from_warehouse_id'])->first();
                    $variant_id = $ot_crm_product_variant_data->variant_id;
                }
                elseif($product_batch_id[$key]) {
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_batch_id[$key] ],
                        ['warehouse_id', $data['from_warehouse_id'] ]
                    ])->first();
                    $product_transfer['product_batch_id'] = $product_batch_id[$key];
                }
                else{
                    $ot_crm_product_from_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($pro_id, $data['from_warehouse_id'])->first();
                }
                //deduct imei number if available
                if($imei_number[$key]) {
                    $imei_numbers = explode(",", $imei_number[$key]);
                    $all_imei_numbers = explode(",", $ot_crm_product_from_warehouse_data->imei_number);
                    foreach ($imei_numbers as $number) {
                        if (($j = array_search($number, $all_imei_numbers)) !== false) {
                            unset($all_imei_numbers[$j]);
                        }
                    }
                    $ot_crm_product_from_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                }

                $ot_crm_product_from_warehouse_data->qty -= $quantity;
                $ot_crm_product_from_warehouse_data->save();
            }

            $product_transfer['product_id'] = $pro_id;
            $product_transfer['variant_id'] = $variant_id;
            $product_transfer['imei_number'] = $imei_number[$key];
            $product_transfer['transfer_id'] = $id;
            $product_transfer['qty'] = $qty[$key];
            $product_transfer['purchase_unit_id'] = $ot_crm_transfer_unit_data->id;
            $product_transfer['net_unit_cost'] = $net_unit_cost[$key];
            $product_transfer['tax_rate'] = $tax_rate[$key];
            $product_transfer['tax'] = $tax[$key];
            $product_transfer['total'] = $total[$key];
            
            if($ot_crm_product_data->is_variant && in_array($product_variant_id[$key], $old_product_variant_id) ) {
                SaleProductTransfer::where([
                    ['transfer_id', $id],
                    ['product_id', $pro_id],
                    ['variant_id', $variant_id]
                ])->update($product_transfer);
            }
            elseif($variant_id == null && in_array($pro_id, $old_product_id) ){
                SaleProductTransfer::where([
                    ['transfer_id', $id],
                    ['product_id', $pro_id]
                ])->update($product_transfer);
            }
            else
                SaleProductTransfer::create($product_transfer);
        }

        $ot_crm_transfer_data->update($data);
        Toastr::success( 'Transfer updated successfully', 'Success');
        return redirect()->route('saleTransfer.index');
    }

    public function deleteBySelection(Request $request)
    {
        $transfer_id = $request['transferIdArray'];
        foreach ($transfer_id as $id) {
            $ot_crm_transfer_data =SaleTransfer::find($id);
            $ot_crm_product_transfer_data = SaleProductTransfer::where('transfer_id', $id)->get();
            foreach ($ot_crm_product_transfer_data as $product_transfer_data) {
                $ot_crm_transfer_unit_data = SaleUnit::find($product_transfer_data->purchase_unit_id);
                if ($ot_crm_transfer_unit_data->operator == '*') {
                    $quantity = $product_transfer_data->qty * $ot_crm_transfer_unit_data->operation_value;
                } else {
                    $quantity = $product_transfer_data / $ot_crm_transfer_unit_data->operation_value;
                }

                if($ot_crm_transfer_data->status == 1) {
                    //add quantity for from warehouse
                    if($product_transfer_data->variant_id)
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                    else
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                    $ot_crm_product_warehouse_data->qty += $quantity;
                    $ot_crm_product_warehouse_data->save();
                    //deduct quantity for to warehouse
                    if($product_transfer_data->variant_id)
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->to_warehouse_id)->first();
                    else
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->to_warehouse_id)->first();

                    $ot_crm_product_warehouse_data->qty -= $quantity;
                    $ot_crm_product_warehouse_data->save();
                }
                elseif($ot_crm_transfer_data->status == 3) {
                    //add quantity for from warehouse
                    if($product_transfer_data->variant_id)
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                    else
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->from_warehouse_id)->first();

                    $ot_crm_product_warehouse_data->qty += $quantity;
                    $ot_crm_product_warehouse_data->save();
                }
                $product_transfer_data->delete();
            }
            $ot_crm_transfer_data->delete();
        }
        return 'Transfer deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_transfer_data =SaleTransfer::find($id);
        $ot_crm_product_transfer_data = SaleProductTransfer::where('transfer_id', $id)->get();
        foreach ($ot_crm_product_transfer_data as $product_transfer_data) {
            $ot_crm_transfer_unit_data = SaleUnit::find($product_transfer_data->purchase_unit_id);
            if ($ot_crm_transfer_unit_data->operator == '*') {
                $quantity = $product_transfer_data->qty * $ot_crm_transfer_unit_data->operation_value;
            } else {
                $quantity = $product_transfer_data / $ot_crm_transfer_unit_data->operation_value;
            }

            if($ot_crm_transfer_data->status == 1) {
                //add quantity for from warehouse
                if($product_transfer_data->variant_id)
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                elseif($product_transfer_data->product_batch_id) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_transfer_data->product_batch_id],
                        ['warehouse_id', $ot_crm_transfer_data->from_warehouse_id]
                    ])->first();
                }
                else
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                //add imei number to from warehouse
                if($product_transfer_data->imei_number) {
                    if($ot_crm_product_warehouse_data->imei_number)
                        $ot_crm_product_warehouse_data->imei_number .= ',' . $product_transfer_data->imei_number;
                    else
                        $ot_crm_product_warehouse_data->imei_number = $product_transfer_data->imei_number;
                }

                $ot_crm_product_warehouse_data->qty += $quantity;
                $ot_crm_product_warehouse_data->save();
                //deduct quantity for to warehouse
                if($product_transfer_data->variant_id)
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->to_warehouse_id)->first();
                elseif($product_transfer_data->product_batch_id) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_transfer_data->product_batch_id],
                        ['warehouse_id', $ot_crm_transfer_data->to_warehouse_id]
                    ])->first();
                }
                else
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->to_warehouse_id)->first();
                //deduct imei number if available
                if($product_transfer_data->imei_number) {
                    $imei_numbers = explode(",", $product_transfer_data->imei_number);
                    $all_imei_numbers = explode(",", $ot_crm_product_warehouse_data->imei_number);
                    foreach ($imei_numbers as $number) {
                        if (($j = array_search($number, $all_imei_numbers)) !== false) {
                            unset($all_imei_numbers[$j]);
                        }
                    }
                    $ot_crm_product_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                }

                $ot_crm_product_warehouse_data->qty -= $quantity;
                $ot_crm_product_warehouse_data->save();
            }
            elseif($ot_crm_transfer_data->status == 3) {
                //add quantity for from warehouse
                if($product_transfer_data->variant_id)
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_transfer_data->product_id, $product_transfer_data->variant_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                elseif($product_transfer_data->product_batch_id) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_transfer_data->product_batch_id],
                        ['warehouse_id', $ot_crm_transfer_data->from_warehouse_id]
                    ])->first();
                }
                else
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_transfer_data->product_id, $ot_crm_transfer_data->from_warehouse_id)->first();
                //add imei number to from warehouse
                if($product_transfer_data->imei_number) {
                    if($ot_crm_product_warehouse_data->imei_number)
                        $ot_crm_product_warehouse_data->imei_number .= ',' . $ot_crm_product_warehouse_data->imei_number;
                    else
                        $ot_crm_product_warehouse_data->imei_number = $ot_crm_product_warehouse_data->imei_number;
                }

                $ot_crm_product_warehouse_data->qty += $quantity;
                $ot_crm_product_warehouse_data->save();
            }
            $product_transfer_data->delete();
        }
        $ot_crm_transfer_data->delete();
        Toastr::success(_trans('response.Transfer deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
