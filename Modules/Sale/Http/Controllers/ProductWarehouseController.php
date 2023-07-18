<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Sale\Entities\SaleWarehouse;
use Illuminate\Contracts\Support\Renderable;

class ProductWarehouseController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] =  _trans('sale.Warehouse List');
        $data['search'] = '';
        $data['warehouses'] = SaleWarehouse::where('is_active',true);
        if ($search != "") {
            $data['warehouses'] = $data['warehouses']->where('name', 'like', '%' . $search . '%');
        }

        $data['warehouses'] = $data['warehouses']->where('is_active', true)->latest()->paginate($entries);

        return view('sale::product.warehouse.index', compact('data'));
    }

    public function store(Request $request)
    {
        
        $input = $request->all();
        $input['is_active'] = true;
        SaleWarehouse::create($input);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleProductWarehouse.index');

    }

    public function edit($id)
    {
        $ot_crm_warehouse_data = SaleWarehouse::findOrFail($id);
        return $ot_crm_warehouse_data;
    }
   
    public function update(Request $request)
    {
       
        $input = $request->all();
        $ot_crm_warehouse_data = SaleWarehouse::find($input['warehouse_id']);
        $ot_crm_warehouse_data->update($input);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleProductWarehouse.index');
    }

    public function importWarehouse(Request $request)
    {
        //get file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        $filename =  $upload->getClientOriginalName();
        $upload=$request->file('file');
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
        //looping through othe columns
        while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
                continue;
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }
           $data= array_combine($escapedHeader, $columns);

           $warehouse = SaleWarehouse::firstOrNew([ 'name'=>$data['name'], 'is_active'=>true ]);
           $warehouse->name = $data['name'];
           $warehouse->phone = $data['phone'];
           $warehouse->email = $data['email'];
           $warehouse->address = $data['address'];
           $warehouse->is_active = true;
           $warehouse->save();
        }
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleProductWarehouse.index');
    }

    public function deleteBySelection(Request $request)
    {
        $warehouse_id = $request['warehouseIdArray'];
        foreach ($warehouse_id as $id) {
            $ot_crm_warehouse_data = SaleWarehouse::find($id);
            $ot_crm_warehouse_data->is_active = false;
            $ot_crm_warehouse_data->save();
        }
        return 'Warehouse deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_warehouse_data = SaleWarehouse::find($id);
        $ot_crm_warehouse_data->is_active = false;
        $ot_crm_warehouse_data->save();
        Toastr::success(_trans('response.Warehouse deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
