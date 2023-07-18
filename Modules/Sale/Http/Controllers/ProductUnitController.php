<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\SaleUnit;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;

class ProductUnitController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] = _trans('sale.Unit List');
        $data['search'] = '';
        $data['units'] = SaleUnit::where('is_active',true);
        $data['search'] = '';
        if ($search != "") {
            $data['units'] = $data['units']->where('unit_name', 'like', '%' . $search . '%');
        }

        $data['units'] = $data['units']->where('is_active', true)->latest()->paginate($entries);
        return view('sale::product.unit.index', compact('data'));
    }

    public function store(Request $request)
    {
        
        $input = $request->all();
        $input['is_active'] = true;
        if(!$input['base_unit']){
            $input['operator'] = '*';
            $input['operation_value'] = 1;
        }
        SaleUnit::create($input);
        Toastr::success('Unit added successfully', 'Success');
        return redirect()->route('saleProductUnit.index');

    }

    public function ot_crmUnitSearch()
    {
        $ot_crm_unit_name = $_GET['ot_crm_unitNameSearch'];
        $ot_crm_unit_all = SaleUnit::where('unit_name', $ot_crm_unit_name)->paginate(5);
        $ot_crm_unit_list = SaleUnit::all();
        return view('backend.unit.create', compact('ot_crm_unit_all','ot_crm_unit_list'));
    }

    public function edit($id)
    {
        $ot_crm_unit_data = SaleUnit::findOrFail($id);
        return $ot_crm_unit_data;
    }

    public function update(Request $request)
    {
        

        $input = $request->all();
        $ot_crm_unit_data = SaleUnit::where('id',$input['unit_id'])->first();
        $ot_crm_unit_data->update($input);
        Toastr::success('Unit updated successfully', 'Success');
        return redirect()->route('saleProductUnit.index');
    }

    public function importUnit(Request $request)
    {  
        //get file
        $filename =  $request->file->getClientOriginalName();
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
        $ot_crm_unit_data = [];
        while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
                continue;
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }
            $data= array_combine($escapedHeader, $columns);

            $unit = SaleUnit::firstOrNew(['unit_code' => $data['code'],'is_active' => true ]);
            $unit->unit_code = $data['code'];
            $unit->unit_name = $data['name'];
            if($data['baseunit']==null)
                $unit->base_unit = null;
            else{
                $base_unit = SaleUnit::where('unit_code', $data['baseunit'])->first();
                $unit->base_unit = $base_unit->id;
            }
            if($data['operator'] == null)
                $unit->operator = '*';
            else
                $unit->operator = $data['operator'];
            if($data['operationvalue'] == null)
                $unit->operation_value = 1;
            else 
                $unit->operation_value = $data['operationvalue'];
            $unit->save();
        }
        Toastr::success('Unit updated successfully', 'Success');
        return redirect()->route('saleProductUnit.index');
        
    }

    public function deleteBySelection(Request $request)
    {
        $unit_id = $request['unitIdArray'];
        foreach ($unit_id as $id) {
            $ot_crm_unit_data = SaleUnit::findOrFail($id);
            $ot_crm_unit_data->is_active = false;
            $ot_crm_unit_data->save();
        }
        return 'Unit deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_unit_data = SaleUnit::findOrFail($id);
        $ot_crm_unit_data->is_active = false;
        $ot_crm_unit_data->save();
        Toastr::success(_trans('response.Unit deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
