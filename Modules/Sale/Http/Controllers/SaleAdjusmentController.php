<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleWarehouse;
use Modules\Sale\Entities\SaleAdjustment;
use Modules\Sale\Entities\SaleStockCount;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleProductVariant;
use Modules\Sale\Entities\SaleProductWarehouse;
use Modules\Sale\Entities\SaleProductAdjustment;

class SaleAdjusmentController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] =  _trans('sale.Adjustment List');
        $data['search'] = '';
        $data['adjustments'] =  SaleAdjustment::latest();
        if ($search != "") {
            $data['adjustments'] = $data['adjustments']->where('reference_no', 'like', '%' . $search . '%');
        }
        $data['adjustments'] = $data['adjustments']->latest()->paginate($entries);

        return view('sale::product.adjustment.index', compact('data'));
    }

    public function getProduct($id)
    {
        $ot_crm_product_warehouse_data = DB::table('sale_products')
                                    ->join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
                                    ->whereNull('sale_products.is_variant')
                                    ->where([
                                        ['sale_products.is_active', true], 
                                        ['sale_product_warehouses.warehouse_id', $id]
                                    ])
                                    ->select('sale_product_warehouses.qty', 'sale_products.code', 'sale_products.name')
                                    ->get();
        $ot_crm_product_withVariant_warehouse_data = DB::table('sale_products')
                                    ->join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
                                    ->whereNotNull('sale_products.is_variant')
                                    ->where([
                                        ['sale_products.is_active', true], 
                                        ['sale_product_warehouses.warehouse_id', $id]
                                    ])
                                    ->select('sale_products.name', 'sale_product_warehouses.qty', 'sale_product_warehouses.product_id', 'sale_product_warehouses.variant_id')
                                    ->get();
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_data = [];
        foreach ($ot_crm_product_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $product_code[] =  $product_warehouse->code;
            $product_name[] = $product_warehouse->name;
        }

        foreach ($ot_crm_product_withVariant_warehouse_data as $product_warehouse) 
        {
            $product_variant = SaleProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
            $product_qty[] = $product_warehouse->qty;
            $product_code[] =  $product_variant->item_code;
            $product_name[] = $product_warehouse->name;
        }

        $product_data[] = $product_code;
        $product_data[] = $product_name;
        $product_data[] = $product_qty;
        return $product_data;
    }

    public function ot_crmProductSearch(Request $request)
    {
        $product_code = explode("(", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $ot_crm_product_data = SaleProduct::where([
            ['code', $product_code[0]],
            ['is_active', true]
        ])->first();
        if(!$ot_crm_product_data) {
            $ot_crm_product_data = SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
                ->select('sale_products.id', 'sale_products.name', 'sale_products.is_variant', 'sale_product_variants.id as product_variant_id', 'sale_product_variants.item_code')
                ->where([
                    ['sale_product_variants.item_code', $product_code[0]],
                    ['sale_products.is_active', true]
                ])->first();
        }

        $product[] = $ot_crm_product_data->name;
        $product_variant_id = null;
        if($ot_crm_product_data->is_variant) {
            $product[] = $ot_crm_product_data->item_code;
            $product_variant_id = $ot_crm_product_data->product_variant_id;
        }
        else
            $product[] = $ot_crm_product_data->code;

        $product[] = $ot_crm_product_data->id;
        $product[] = $product_variant_id;
        return $product;
    }

    public function create()
    {
        $data['title'] = _trans('sale.Adjustment Create');
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        return view('sale::product.adjustment.create', compact('data','ot_crm_warehouse_list'));
    }

    public function store(Request $request)
    {
        $data = $request->except('document');
        //return $data;
        if( isset($data['stock_count_id']) ){
            $ot_crm_stock_count_data = SaleStockCount::find($data['stock_count_id']);
            $ot_crm_stock_count_data->is_adjusted = true;
            $ot_crm_stock_count_data->save();
        }
        $data['reference_no'] = 'adr-' . date("Ymd") . '-'. date("his");
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/documents/adjustment', $documentName);
            $data['document'] = $documentName;
        }
        $ot_crm_adjustment_data = SaleAdjustment::create($data);

        $product_id = $data['product_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $action = $data['action'];

        foreach ($product_id as $key => $pro_id) {
            $ot_crm_product_data = SaleProduct::find($pro_id);
            if($ot_crm_product_data->is_variant) {
                $ot_crm_product_variant_data = SaleProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $pro_id],
                    ['variant_id', $ot_crm_product_variant_data->variant_id ],
                    ['warehouse_id', $data['warehouse_id'] ],
                ])->first();

                if($action[$key] == '-'){
                    $ot_crm_product_variant_data->qty -= $qty[$key];
                }
                elseif($action[$key] == '+'){
                    $ot_crm_product_variant_data->qty += $qty[$key];
                }
                $ot_crm_product_variant_data->save();
                $variant_id = $ot_crm_product_variant_data->variant_id;
            }
            else {
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $pro_id],
                    ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                $variant_id = null;
            }

            if($action[$key] == '-') {
                $ot_crm_product_data->qty -= $qty[$key];
                $ot_crm_product_warehouse_data->qty -= $qty[$key];
            }
            elseif($action[$key] == '+') {
                $ot_crm_product_data->qty += $qty[$key];
                $ot_crm_product_warehouse_data->qty += $qty[$key];
            }
            $ot_crm_product_data->save();
            $ot_crm_product_warehouse_data->save();

            $product_adjustment['product_id'] = $pro_id;
            $product_adjustment['variant_id'] = $variant_id;
            $product_adjustment['adjustment_id'] = $ot_crm_adjustment_data->id;
            $product_adjustment['qty'] = $qty[$key];
            $product_adjustment['action'] = $action[$key];
            SaleProductAdjustment::create($product_adjustment);
        }
        Toastr::success( 'Data inserted successfully', 'Success');
        return redirect()->route('saleAdjustment.index');
    }

    public function edit($id)
    {
        $ot_crm_adjustment_data = SaleAdjustment::find($id);
        $ot_crm_product_adjustment_data = SaleProductAdjustment::where('adjustment_id', $id)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $data['title'] = _trans('sale.Adjustment Edit');
        return view('sale::product.adjustment.edit', compact('data','ot_crm_adjustment_data', 'ot_crm_warehouse_list', 'ot_crm_product_adjustment_data'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/documents/adjustment', $documentName);
            $data['document'] = $documentName;
        }

        $ot_crm_adjustment_data = SaleAdjustment::find($id);
        $ot_crm_product_adjustment_data = SaleProductAdjustment::where('adjustment_id', $id)->get();
        $product_id = $data['product_id'];
        $product_variant_id = $data['product_variant_id'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $action = $data['action'];
        $old_product_variant_id = [];
        foreach ($ot_crm_product_adjustment_data as $key => $product_adjustment_data) {
            $old_product_id[] = $product_adjustment_data->product_id;
            $ot_crm_product_data = SaleProduct::find($product_adjustment_data->product_id);
            if($product_adjustment_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::where([
                    ['product_id', $product_adjustment_data->product_id],
                    ['variant_id', $product_adjustment_data->variant_id]
                ])->first();
                $old_product_variant_id[$key] = $ot_crm_product_variant_data->id;

                if($product_adjustment_data->action == '-') {
                    $ot_crm_product_variant_data->qty += $product_adjustment_data->qty;
                }
                elseif($product_adjustment_data->action == '+') {
                    $ot_crm_product_variant_data->qty -= $product_adjustment_data->qty;
                }
                $ot_crm_product_variant_data->save();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $product_adjustment_data->product_id],
                    ['variant_id', $product_adjustment_data->variant_id],
                    ['warehouse_id', $ot_crm_adjustment_data->warehouse_id]
                ])->first();
            }
            else {
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['warehouse_id', $ot_crm_adjustment_data->warehouse_id]
                    ])->first();
            }
            if($product_adjustment_data->action == '-'){
                $ot_crm_product_data->qty += $product_adjustment_data->qty;
                $ot_crm_product_warehouse_data->qty += $product_adjustment_data->qty;
            }
            elseif($product_adjustment_data->action == '+'){
                $ot_crm_product_data->qty -= $product_adjustment_data->qty;
                $ot_crm_product_warehouse_data->qty -= $product_adjustment_data->qty;
            }
            $ot_crm_product_data->save();
            $ot_crm_product_warehouse_data->save();

            if($product_adjustment_data->variant_id && !(in_array($old_product_variant_id[$key], $product_variant_id)) ){
                $product_adjustment_data->delete();
            }
            elseif( !(in_array($old_product_id[$key], $product_id)) )
                $product_adjustment_data->delete();
        }

        foreach ($product_id as $key => $pro_id) {
            $ot_crm_product_data = SaleProduct::find($pro_id);
            if($ot_crm_product_data->is_variant) {
                $ot_crm_product_variant_data = SaleProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $pro_id],
                    ['variant_id', $ot_crm_product_variant_data->variant_id ],
                    ['warehouse_id', $data['warehouse_id'] ],
                ])->first();
                //return $action[$key];

                if($action[$key] == '-'){
                    $ot_crm_product_variant_data->qty -= $qty[$key];
                }
                elseif($action[$key] == '+'){
                    $ot_crm_product_variant_data->qty += $qty[$key];
                }
                $ot_crm_product_variant_data->save();
                $variant_id = $ot_crm_product_variant_data->variant_id;
            }
            else {
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                    ['product_id', $pro_id],
                    ['warehouse_id', $data['warehouse_id'] ],
                    ])->first();
                $variant_id = null;
            }

            if($action[$key] == '-'){
                $ot_crm_product_data->qty -= $qty[$key];
                $ot_crm_product_warehouse_data->qty -= $qty[$key];
            }
            elseif($action[$key] == '+'){
                $ot_crm_product_data->qty += $qty[$key];
                $ot_crm_product_warehouse_data->qty += $qty[$key];
            }
            $ot_crm_product_data->save();
            $ot_crm_product_warehouse_data->save();

            $product_adjustment['product_id'] = $pro_id;
            $product_adjustment['variant_id'] = $variant_id;
            $product_adjustment['adjustment_id'] = $id;
            $product_adjustment['qty'] = $qty[$key];
            $product_adjustment['action'] = $action[$key];

            if($product_adjustment['variant_id'] && in_array($product_variant_id[$key], $old_product_variant_id)) {
                SaleProductAdjustment::where([
                    ['product_id', $pro_id],
                    ['variant_id', $product_adjustment['variant_id']],
                    ['adjustment_id', $id]
                ])->update($product_adjustment);
            }
            elseif( $product_adjustment['variant_id'] === null && in_array($pro_id, $old_product_id) ){
                SaleProductAdjustment::where([
                ['adjustment_id', $id],
                ['product_id', $pro_id]
                ])->update($product_adjustment);
            }
            else
                SaleProductAdjustment::create($product_adjustment);
        }
        $ot_crm_adjustment_data->update($data);
        Toastr::success( 'Data updated successfully', 'Success');
        return redirect()->route('saleAdjustment.index');
    }

    public function destroy($id)
    {
        $ot_crm_adjustment_data = SaleAdjustment::find($id);
        $ot_crm_product_adjustment_data = SaleProductAdjustment::where('adjustment_id', $id)->get();
        foreach ($ot_crm_product_adjustment_data as $key => $product_adjustment_data) {
            $ot_crm_product_data = SaleProduct::find($product_adjustment_data->product_id);
            if($product_adjustment_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($product_adjustment_data->product_id, $product_adjustment_data->variant_id)->first();
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['variant_id', $product_adjustment_data->variant_id],
                        ['warehouse_id', $ot_crm_adjustment_data->warehouse_id]
                    ])->first();
                if($product_adjustment_data->action == '-'){
                    $ot_crm_product_variant_data->qty += $product_adjustment_data->qty;
                }
                elseif($product_adjustment_data->action == '+'){
                    $ot_crm_product_variant_data->qty -= $product_adjustment_data->qty;
                }
                $ot_crm_product_variant_data->save();
            }
            else {
                $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['warehouse_id', $ot_crm_adjustment_data->warehouse_id]
                    ])->first();
            }
            if($product_adjustment_data->action == '-'){
                $ot_crm_product_data->qty += $product_adjustment_data->qty;
                $ot_crm_product_warehouse_data->qty += $product_adjustment_data->qty;
            }
            elseif($product_adjustment_data->action == '+'){
                $ot_crm_product_data->qty -= $product_adjustment_data->qty;
                $ot_crm_product_warehouse_data->qty -= $product_adjustment_data->qty;
            }
            $ot_crm_product_data->save();
            $ot_crm_product_warehouse_data->save();
            $product_adjustment_data->delete();
        }
        $ot_crm_adjustment_data->delete();
        Toastr::success(_trans('response.Data deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);

    }

}
