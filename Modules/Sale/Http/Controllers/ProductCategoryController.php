<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use App\Exports\SaleCategoryExport;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Goodby\CSV\Export\Standard\Stream;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleCategory;
use Illuminate\Support\Facades\Response;

class ProductCategoryController extends Controller
{

    public function exportCsv()
    {
        $categories = SaleCategory::select('id', 'name', 'image', 'parent_id', 'is_active', 'created_at')->get();
        return Excel::download(new SaleCategoryExport(new Collection($categories)), 'sale_categories_'.time().'.csv');
    }

    public function export(Request $request)
    {
        try {
            $entries = $request->input('entries', 10);
            $search = $request->input('search', '');
            $export_type = $request->input('export_type', '');

            $data['entries'] = $entries;
            $data['search'] = $search;

            $data['categories'] = SaleCategory::with('product:price,cost,qty', 'parent')
                ->when($search != '', function ($query) use ($search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                })
                ->where('is_active', true)
                ->latest()
                ->paginate($entries);

            $ot_crm_categories = SaleCategory::where('is_active', true)->pluck('name', 'id');
            $data['title'] = _trans('sale.Category List');

            // Export as CSV
            if ($export_type === 'csv') {
                $fileName = 'categories.csv';
                $headers = [
                    "Content-type" => "text/csv",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma" => "no-cache",
                    "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                    "Expires" => "0",
                ];

                $columns = ['ID', 'Name', 'Parent', 'Created At', 'Updated At', 'Product Count'];

                $callback = function () use ($data, $columns) {
                    $file = fopen('php://output', 'w');
                    fputcsv($file, $columns);

                    foreach ($data['categories'] as $category) {
                        $parentName = $category->parent ? @$category->parent->name : '';
                        $createdAt = $category->created_at->format('Y-m-d H:i:s');
                        $updatedAt = $category->updated_at->format('Y-m-d H:i:s');
                        $productCount = $category->product->count();

                        $row = [$category->id, $category->name, $parentName, $createdAt, $updatedAt, $productCount];
                        fputcsv($file, $row);
                    }

                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }

            dd($request->all());
        } catch (\Throwable$th) {
            dd($th);
        }
    }

    public function index(Request $request)
    {

        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;

        $ot_crm_categories = SaleCategory::where('is_active', true)->pluck('name', 'id');
        $data['title'] = _trans('sale.Category List');
        $data['categories'] = SaleCategory::with('product:price,cost,qty', 'parent');
        $data['search'] = '';
        if ($search != "") {
            $data['categories'] = $data['categories']->where('name', 'like', '%' . $search . '%');
        }

        $data['categories'] = $data['categories']->where('is_active', true)->latest()->paginate($entries);
        return view('sale::product.category.index', compact('ot_crm_categories', 'data'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:sale_categories|max:255',
        ]);

        $request->name = preg_replace('/\s+/', ' ', $request->name);

        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = date("Ymdhis");
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/category', $imageName);

            $ot_crm_category_data['image'] = $imageName;
        }
        $ot_crm_category_data['name'] = $request->name;
        $ot_crm_category_data['parent_id'] = $request->parent_id;
        $ot_crm_category_data['is_active'] = true;
        SaleCategory::create($ot_crm_category_data);

        Toastr::success(_trans('response.Category added successfully'), 'Success');
        return redirect()->route('saleProductCategory.index');
    }

    public function edit($id)
    {
        $ot_crm_category_data = SaleCategory::findOrFail($id);
        $ot_crm_parent_data = SaleCategory::where('id', $ot_crm_category_data['parent_id'])->first();
        if ($ot_crm_category_data['image'] != "") {
            $ot_crm_category_data['image'] = asset('public/images/category/' . $ot_crm_category_data['image']);
        }else{
            $ot_crm_category_data['image'] = asset('public/static/blank_small.png');
        }
        if ($ot_crm_parent_data) {
            $ot_crm_category_data['parent'] = $ot_crm_parent_data['name'];
        }

        // image
        return $ot_crm_category_data;
    }

    public function update(Request $request)
    {
        $input = $request->except('image');
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = date("Ymdhis");
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/category', $imageName);
            $input['image'] = $imageName;
        }
        $ot_crm_category_data = SaleCategory::findOrFail($request->category_id);
        $ot_crm_category_data->update($input);
        Toastr::success(_trans('response.Category updated successfully'), 'Success');
        return redirect()->route('saleProductCategory.index');
    }

    public function importCategory(Request $request)
    {

        //get file
        $upload = $request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if ($ext != 'csv') {
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        }

        $filename = $upload->getClientOriginalName();
        $filePath = $upload->getRealPath();
        //open and read
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $escapedHeader = [];
        //validate
        foreach ($header as $key => $value) {
            $lheader = strtolower($value);
            $escapedItem = preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through othe columns
        while ($columns = fgetcsv($file)) {
            if ($columns[0] == "") {
                continue;
            }

            foreach ($columns as $key => $value) {
                $value = preg_replace('/\D/', '', $value);
            }
            $data = array_combine($escapedHeader, $columns);
            $category = SaleCategory::firstOrNew(['name' => $data['name'], 'is_active' => true]);
            if ($data['parentcategory']) {
                $parent_category = SaleCategory::firstOrNew(['name' => $data['parentcategory'], 'is_active' => true]);
                $parent_id = $parent_category->id;
            } else {
                $parent_id = null;
            }

            $category->parent_id = $parent_id;
            $category->is_active = true;
            $category->save();
        }
        Toastr::success(_trans('response.Category imported successfully'), 'Success');
        return redirect()->route('saleProductCategory.index');
    }

    public function deleteBySelection(Request $request)
    {
        $category_id = $request['categoryIdArray'];
        foreach ($category_id as $id) {
            $ot_crm_product_data = SaleProduct::where('category_id', $id)->get();
            foreach ($ot_crm_product_data as $product_data) {
                $product_data->is_active = false;
                $product_data->save();
            }
            $ot_crm_category_data = SaleCategory::findOrFail($id);
            if ($ot_crm_category_data->image) {
                unlink('public/images/category/' . $ot_crm_category_data->image);
            }

            $ot_crm_category_data->is_active = false;
            $ot_crm_category_data->save();
        }
        // return 'Category deleted successfully!';
        Toastr::success(_trans('response.Category deleted successfully'), 'Success');
        return redirect()->route('saleProductCategory.index');
    }

    public function destroy($id)
    {
        $ot_crm_category_data = SaleCategory::findOrFail($id);
        $ot_crm_category_data->is_active = false;
        $ot_crm_product_data = SaleProduct::where('category_id', $id)->get();
        foreach ($ot_crm_product_data as $product_data) {
            $product_data->is_active = false;
            $product_data->save();
        }
        if ($ot_crm_category_data->image) {
            unlink('public/images/category/' . $ot_crm_category_data->image);
        }

        $ot_crm_category_data->save();
        Toastr::success(_trans('response.Category deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
