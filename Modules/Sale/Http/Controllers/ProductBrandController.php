<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Sale\Entities\SaleBrand;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;

class ProductBrandController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        
        $data['title'] = _trans('common.Brand List');
        $data['brands'] = SaleBrand::where('is_active',true);
        $data['search'] = '';
        if ($search != "") {
            $data['brands'] = $data['brands']->where('title', 'like', '%' . $search . '%');
        }

        $data['brands'] = $data['brands']->where('is_active', true)->latest()->paginate($entries);
        return view('sale::product.brand.index', compact('data'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:sale_brands|max:255',
        ]);

        if ($validator->fails()) {

            foreach ($validator->errors()->all() as $message)
            {
                Toastr::error($message, 'Error');
            }
            return redirect()->back();
        }

        $input = $request->except('image');
        $input['is_active'] = true;
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = date("Ymdhis");
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/brand', $imageName);
            $input['image'] = $imageName;
        }
        SaleBrand::create($input);

        Toastr::success( _trans('response.Brand added successfully'), 'Success');
        return redirect()->route('saleProductBrand.index');
    }

    public function edit($id)
    {
        $data = SaleBrand::findOrFail($id);
        $brand =  [
            'id'=>$data->id,
            'title'=>$data->title,
            'image' => $data->image ? asset('public/images/brand/'.$data->image) : asset('public/static/blank_small.png')
            
        ];


        return $brand;
    }

    public function update(Request $request)
    {
       
        $ot_crm_brand_data = SaleBrand::findOrFail($request->brand_id);
        $ot_crm_brand_data->title = $request->title;
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = date("Ymdhis");
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/brand', $imageName);
            $ot_crm_brand_data->image = $imageName;
        }
        $ot_crm_brand_data->save();
        Toastr::success( _trans('response.Brand Updated successfully'), 'Success');
        return redirect()->route('saleProductBrand.index');
    }

    public function importBrand(Request $request)
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

           $brand = SaleBrand::firstOrNew([ 'title'=>$data['title'], 'is_active'=>true ]);
           $brand->title = $data['title'];
           $brand->image = $data['image'];
           $brand->is_active = true;
           $brand->save();
        }
        return redirect()->route('saleProductBrand.index')->with('message', 'Brand imported successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $brand_id = $request['brandIdArray'];
        foreach ($brand_id as $id) {
            $ot_crm_brand_data = SaleBrand::findOrFail($id);
            $ot_crm_brand_data->is_active = false;
            $ot_crm_brand_data->save();
        }
        return 'Brand deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_brand_data = SaleBrand::findOrFail($id);
        $ot_crm_brand_data->delete();
        Toastr::success(_trans('response.Brand deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }

    public function exportBrand(Request $request)
    {
        $ot_crm_brand_data = $request['brandArray'];
        $csvData=array('Brand Title, Image');
        foreach ($ot_crm_brand_data as $brand) {
            if($brand > 0) {
                $data = SaleBrand::where('id', $brand)->first();
                $csvData[]=$data->title.','.$data->image;
            }   
        }        
        $filename=date('Y-m-d').".csv";
        $file_path=public_path().'/downloads/'.$filename;
        $file_url=url('/').'/downloads/'.$filename;   
        $file = fopen($file_path,"w+");
        foreach ($csvData as $exp_data){
          fputcsv($file,explode(',',$exp_data));
        }   
        fclose($file);
        return $file_url;
    }
}
