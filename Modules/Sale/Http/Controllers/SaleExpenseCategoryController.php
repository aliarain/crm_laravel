<?php

namespace Modules\Sale\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\SaleExpenseCategory;

class SaleExpenseCategoryController extends Controller
{
    protected $successMessage;
    protected $errorMessage;

    public function __construct()
    {
        $this->successMessage = _trans('message.Operation Successful');
        $this->errorMessage = _trans('message.Operation Failed');
    }
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] = _trans('sale.Expense Category List');
        $data['search'] = '';
        $data['categories'] = SaleExpenseCategory::where('is_active', true);
        if ($search != "") {
            $data['categories'] = $data['categories']->where('name', 'like', '%' . $search . '%');
        }

        $data['categories'] = $data['categories']->where('is_active', true)->latest()->paginate($entries);

        return view('sale::expense_category.index', compact('data'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|unique:sale_expense_categories|max:255',
        ]);
        try {

            // Get the request data
            $data = $request->all();

            // Create a new SaleExpenseCategory record with the request data
            SaleExpenseCategory::create($data);

            // Flash a success message to the user
            Toastr::success($this->successMessage, 'Success');

            // Redirect to the index page
            return redirect()->route('saleProductExpenseCategory.index');
        } catch (\Exception$e) {
            // If an exception occurs, log the error and flash an error message to the user
            \Log::error($e->getMessage());
            Toastr::error('An error occurred while saving the data.', 'Error');

            // Redirect back to the create form with the old input data
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $ot_crm_expense_category_data = SaleExpenseCategory::find($id);
        return $ot_crm_expense_category_data;
    }

    public function update(Request $request)
    {
        try {
            // Get the request data
            $data = $request->all();

            // Find the expense category data to update
            $expenseCategoryData = SaleExpenseCategory::find($data['expense_category_id']);

            // Update the expense category data
            $expenseCategoryData->update($data);

            // Display success message
            Toastr::success($this->successMessage, 'Success');

            // Redirect to the index page
            return redirect()->route('saleProductExpenseCategory.index');
        } catch (\Exception$e) {
            // Display error message and redirect back to the previous page if there's an error
            Toastr::error($e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function importcategory(Request $request)
    {
        try {
            // Get file
            $upload = $request->file('file');

            // Check file extension
            $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
            if ($ext != 'csv') {
                return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
            }

            // Get file name and path
            $filename = $upload->getClientOriginalName();
            $filePath = $upload->getRealPath();

            // Open file
            $file = fopen($filePath, 'r');

            // Get header and validate
            $header = fgetcsv($file);
            $escapedHeader = [];
            foreach ($header as $key => $value) {
                $lheader = strtolower($value);
                $escapedItem = preg_replace('/[^a-z]/', '', $lheader);
                array_push($escapedHeader, $escapedItem);
            }

            // Loop through each row of the file
            while ($columns = fgetcsv($file)) {
                // Skip empty rows
                if ($columns[0] == "") {
                    continue;
                }

                // Sanitize values
                foreach ($columns as $key => $value) {
                    $value = preg_replace('/\D/', '', $value);
                    $columns[$key] = $value;
                }

                // Combine header and values into an associative array
                $data = array_combine($escapedHeader, $columns);

                // Check if the category already exists
                $expense_category = SaleExpenseCategory::firstOrNew(['code' => $data['code'], 'is_active' => true]);

                // Update or create the category
                $expense_category->code = $data['code'];
                $expense_category->name = $data['name'];
                $expense_category->is_active = true;
                $expense_category->save();
            }

            // Redirect with success message
            Toastr::success($this->successMessage, 'Success');
            return redirect()->route('saleProductExpenseCategory.index');
        } catch (\Exception$e) {
            // Handle exceptions and redirect with error message
            Toastr::error($e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    public function deleteBySelection(Request $request)
    {
        // Get an array of expense category IDs to delete
        $expense_category_ids = $request->input('expense_categoryIdArray', []);
        try {
            // Loop through each expense category ID and set is_active to false
            foreach ($expense_category_ids as $id) {
                // Find the SaleExpenseCategory by ID
                $sale_expense_category = SaleExpenseCategory::findOrFail($id);
                // Set the is_active flag to false and save the SaleExpenseCategory
                $sale_expense_category->is_active = false;
                $sale_expense_category->save();
            }
            // Return a success message
            return $this->successMessage;
        } catch (\Exception$e) {
            Toastr::error($e->getMessage(), 'Error');
            // Return an error message
            return $this->errorMessage;
        }
    }

    public function destroy($id)
    {
        try {
            // Find the SaleExpenseCategory by ID
            $sale_expense_category = SaleExpenseCategory::findOrFail($id);
            // Set the is_active flag to false and save the SaleExpenseCategory
            $sale_expense_category->is_active = false;
            $sale_expense_category->save();

            Toastr::success(_trans('response.Expense Category deleted successfully'), 'Success');
            return response()->json(['status' => 'success']);
        } catch (\Exception$e) {
            // Show an error message using Toastr and redirect back to the index page
            Toastr::error($this->errorMessage, 'Error');
            return redirect()->back();
        }
    }

}
