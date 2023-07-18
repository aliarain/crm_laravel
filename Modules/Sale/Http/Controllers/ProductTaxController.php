<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\SaleTax;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;

class ProductTaxController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] = _trans('sale.Tax List');
        $data['search'] = '';
        $data['taxs'] = SaleTax::where('is_active',true);
        $data['search'] = '';
        if ($search != "") {
            $data['taxs'] = $data['taxs']->where('name', 'like', '%' . $search . '%');
        }

        $data['taxs'] = $data['taxs']->where('is_active', true)->latest()->paginate($entries);

        return view('sale::product.tax.index', compact('data'));
    }

    public function store(Request $request)
    {
        
        $input = $request->all();
        $input['is_active'] = true;
        SaleTax::create($input);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleProductTax.index');

    }

    public function ot_crmTaxSearch()
    {
        $ot_crm_tax_name = $_GET['ot_crm_taxNameSearch'];
        $ot_crm_tax_all = SaleTax::where('name', $ot_crm_tax_name)->paginate(5);
        $ot_crm_tax_list = SaleTax::all();
        return view('sale::product.tax.index', compact('ot_crm_tax_all','ot_crm_tax_list'));
    }

    public function edit($id)
    {
        $ot_crm_tax_data = SaleTax::findOrFail($id);
        return $ot_crm_tax_data;
    }

    public function update(Request $request)
    {
        

        $input = $request->all();
        $ot_crm_tax_data = SaleTax::where('id', $input['tax_id'])->first();
        $ot_crm_tax_data->update($input);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleProductTax.index');
    }

    public function importTax(Request $request)
    {  
        //get file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        $filename =  $upload->getClientOriginalName();
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

           $tax = SaleTax::firstOrNew(['name' => $data['name'], 'is_active' => true ]);
           $tax->name = $data['name'];
           $tax->rate = $data['rate'];
           $tax->is_active = true;
           $tax->save();
        }
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleProductTax.index');
    }

    public function deleteBySelection(Request $request)
    {
        $tax_id = $request['taxIdArray'];
        foreach ($tax_id as $id) {
            $ot_crm_tax_data = SaleTax::findOrFail($id);
            $ot_crm_tax_data->is_active = false;
            $ot_crm_tax_data->save();
        }
        return 'Tax deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_tax_data = SaleTax::findOrFail($id);
        $ot_crm_tax_data->is_active = false;
        $ot_crm_tax_data->save();
        Toastr::success(_trans('response.Tax deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
