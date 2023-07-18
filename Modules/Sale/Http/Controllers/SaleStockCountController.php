<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleBrand;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleCategory;
use Modules\Sale\Entities\SaleWarehouse;
use Modules\Sale\Entities\SaleStockCount;
use Illuminate\Contracts\Support\Renderable;

class SaleStockCountController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
            $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
            $ot_crm_brand_list = SaleBrand::where('is_active', true)->get();
            $ot_crm_category_list = SaleCategory::where('is_active', true)->get();
            $data['title'] = _trans('sale.Stock Count');
            $data['search'] = '';
            $data['stockCounts'] = SaleStockCount::with('warehouse');
            
            if ($search != "") {
                $data['stockCounts'] = $data['stockCounts']->where('reference_no', 'like', '%' . $search . '%');
            }
            $data['stockCounts'] = $data['stockCounts']->latest()->paginate($entries);


            return view('sale::product.stock_count.index', compact('ot_crm_warehouse_list', 'ot_crm_brand_list', 'ot_crm_category_list','data'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $ot_crm_product_list = DB::table('sale_products')->join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')->where([ ['sale_products.is_active', true], ['sale_product_warehouses.warehouse_id', $data['warehouse_id']] ])->select('sale_products.name', 'sale_products.code', 'sale_product_warehouses.imei_number', 'sale_product_warehouses.qty')->get();
        if( count($ot_crm_product_list) ){
            $csvData=array('Product Name, Product Code, Expected, Counted');
            foreach ($ot_crm_product_list as $product) {
                $csvData[]=$product->name.','.$product->code.','.$product->qty.','.'';
            }
            //return $csvData;
            $filename= date('Ymd').'-'.date('his'). ".csv";
            $file_path= 'Modules/Sale/public/stock_count/'.$filename;
            $file = fopen($file_path, "w+");
            foreach ($csvData as $cellData){
              fputcsv($file, explode(',', $cellData));
            }
            fclose($file);
            

            $data['user_id'] = Auth::id();
            $data['reference_no'] = 'scr-' . date("Ymd") . '-'. date("his");
            $data['initial_file'] = 'Modules/Sale/public/stock_count/'.$filename;
            $data['is_adjusted'] = false;
            SaleStockCount::create($data);
            Toastr::success( 'Stock Count created successfully! Please download the initial file to complete it.', 'Success');
            return redirect()->back();
        }
        else
            Toastr::error( 'No product found!', 'Error');
            return redirect()->back();
    }

    public function finalize(Request $request)
    {
        $ext = pathinfo($request->final_file->getClientOriginalName(), PATHINFO_EXTENSION);
        //checking if this is a CSV file

        if($ext != 'csv'){ 
            Toastr::error( 'Please upload a CSV file', 'Error');
            return redirect()->back();
        }
        $data = $request->all();
        $document = $request->final_file;
        $documentName = date('Ymd').'-'.date('his'). ".csv";
        $document->move('Modules/sale/public/stock_count/', $documentName);
        $data['final_file'] = module_url_path("sale").'/stock_count/'.$documentName;
        $ot_crm_stock_count_data = SaleStockCount::find($data['stock_count_id']);
        $ot_crm_stock_count_data->update($data);
        Toastr::success( 'Stock Count finalized successfully!', 'Success');
        return redirect()->back();
    }

    public function stockDif($id)
    {
        $ot_crm_stock_count_data = SaleStockCount::find($id);
        $file_handle = fopen($ot_crm_stock_count_data->final_file, 'r');
        $i = 0;
        $temp_dif = -1000000;
        $data = [];
        $product = [];
        while( !feof($file_handle) ) {
            $current_line = fgetcsv($file_handle);
            if( $current_line && $i > 0 && ($current_line[2] != $current_line[3]) ){
                $product[] = $current_line[0].' ['.$current_line[1].']';
                $expected[] = $current_line[2];
                $product_data = SaleProduct::where('code', $current_line[1])->first();
                if($current_line[3]){
                    $difference[] = $temp_dif = $current_line[3] - $current_line[2];
                    $counted[] = $current_line[3];
                }
                else{
                    $difference[] = $temp_dif = $current_line[2] * (-1);
                    $counted[] = 0;
                }
                $cost[] = $product_data->cost * $temp_dif;
            }
            $i++;
        }
        if($temp_dif == -1000000){
            $ot_crm_stock_count_data->is_adjusted = true;
            $ot_crm_stock_count_data->save();
        }
        if( count($product) ) {
            $data[] = $product;
            $data[] = $expected;
            $data[] = $counted;
            $data[] = $difference;
            $data[] = $cost;
            $data[] = $ot_crm_stock_count_data->is_adjusted;
        }
        return $data;
    }

    public function qtyAdjustment($id)
    {
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_stock_count_data = SaleStockCount::find($id);
        $warehouse_id = $ot_crm_stock_count_data->warehouse_id;
        $file_handle = fopen($ot_crm_stock_count_data->final_file, 'r');
        $i = 0;
        $product_id = [];
        while( !feof($file_handle) ) {
            $current_line = fgetcsv($file_handle);
            if( $current_line && $i > 0 && ($current_line[2] != $current_line[3]) ){
                $product_data = SaleProduct::where('code', $current_line[1])->first();
                $product_id[] = $product_data->id;
                $names[] = $current_line[0];
                $code[] = $current_line[1];

                if($current_line[3])
                    $temp_qty = $current_line[3] - $current_line[2];
                else
                    $temp_qty = $current_line[2] * (-1);

                if($temp_qty < 0){
                    $qty[] = $temp_qty * (-1);
                    $action[] = '-';  
                }
                else{
                    $qty[] = $temp_qty;
                    $action[] = '+';
                }
            }
            $i++;
        }
        return view('sale::product.stock_count.qty_adjustment', compact('ot_crm_warehouse_list', 'warehouse_id', 'id', 'product_id', 'names', 'code', 'qty', 'action'));
    }
}
