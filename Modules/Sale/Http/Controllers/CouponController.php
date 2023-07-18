<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleCoupon;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{

    /**
     * Display a list of active coupons.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        // Set the title of the page using the _trans() function for translation.
        $data['title'] = _trans('common.Coupon List');
        // Fetch the active coupons from the database, including the associated user, and order them by ID in descending order.
        $data['coupons'] = SaleCoupon::with('user')->where('is_active', true);
        if ($search != "") {
            $data['coupons'] = $data['coupons']->where('code', 'like', '%' . $search . '%');
        }

        $data['coupons'] = $data['coupons']->where('is_active', true)->latest()->paginate($entries);
        // Return the view with the data passed in using the compact() function.
        return view('sale::sale.coupon.index', compact('data'));
    }

    /**
     * Generates a random code consisting of 6 digits.
     *
     * @return int The generated code.
     */
    public function generateCode(): int
    {
        $min = 100000; // The minimum value for the code.
        $max = 999999; // The maximum value for the code.
        $code = random_int($min, $max); // Generate a random integer between $min and $max (inclusive).
        return $code;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Get all the data from the request
        $data = $request->all();

        // Set the initial values of some fields
        $data['used'] = 0; // Default value for "used" field is 0
        $data['user_id'] = Auth::id(); // Set the user ID of the authenticated user
        $data['is_active'] = true; // Default value for "is_active" field is true

        // Create a new SaleCoupon object with the data and save it to the database
        SaleCoupon::create($data);

        // Show a success message using Toastr library
        Toastr::success(_trans('response.Operation Successfuly'), 'Success');

        // Redirect to the index page of the SaleCoupon resource
        return redirect()->route('saleCoupon.index');
    }

    /**
     * Retrieves a SaleCoupon model by ID for editing.
     *
     * @param int $id The ID of the SaleCoupon to retrieve.
     * @return SaleCoupon|null The SaleCoupon model, or null if it was not found.
     */
    public function edit($id)
    {
        try {
            // Retrieve the SaleCoupon model by ID.
            $data = SaleCoupon::findOrFail($id);

            // Return the model.
            return $data;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException$exception) {
            // If the model was not found, return null and log an error.
            \Log::error("SaleCoupon with ID $id not found: " . $exception->getMessage());
            return null;
        } catch (\Exception$exception) {
            // If an unexpected exception occurs, log an error and rethrow the exception.
            \Log::error("Error retrieving SaleCoupon with ID $id: " . $exception->getMessage());
            throw $exception;
        }
    }

    public function update(Request $request)
    {
        try {
            // Validate input
            $validatedData = $request->validate([
                'coupon_id' => 'required|numeric',
                'type' => 'required|in:percentage,amount',
                'minimum_amount' => 'nullable|numeric',
            ]);
            // Update minimum_amount if type is 'percentage'
            if ($validatedData['type'] == 'percentage') {
                $validatedData['minimum_amount'] = 0;
            }

            // Find SaleCoupon record by coupon_id and update data
            $sale_coupon = SaleCoupon::findOrFail($validatedData['coupon_id']);
            $sale_coupon->update($validatedData);

            // Display success message
            Toastr::success(_trans('response.Operation Successfuly'), 'Success');
            // Redirect to index page
            return redirect()->route('saleCoupon.index');
        } catch (\Exception$e) {
            // Display error message
            Toastr::error($e->getMessage(), 'Error');
            // Redirect back to previous page with input data
            return redirect()->back()->withInput($request->input());
        }
    }

    /**
     * Delete selected coupons by setting is_active to false
     *
     * @param Request $request
     * @return string
     * @throws \Exception
     */
    public function deleteBySelection(Request $request)
    {
        try {
            // Validate input data
            $coupon_id = $request->input('couponIdArray');
            if (!is_array($coupon_id)) {
                throw new \InvalidArgumentException('Invalid coupon ID array');
            }

            // Loop through each coupon ID and set is_active to false
            foreach ($coupon_id as $id) {
                // Find the coupon data
                $sale_coupon = SaleCoupon::findOrFail($id);

                // Set is_active to false
                $sale_coupon->is_active = false;
                $sale_coupon->save();
            }

            // Return success message
            return _trans('response.Coupon deleted successfully!');
        } catch (\Throwable$e) {
            // Log the error
            \Log::error($e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // Return error message
            return _trans('response.Error occurred while deleting coupons');
        }
    }

    public function destroy($id)
    {
        try {
            // Validate input
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|numeric|exists:sale_coupons,id',
            ]);

            if ($validator->fails()) {
                Toastr::error(_trans('response.Invalid Input'), 'Error');
                return redirect()->back()->withErrors($validator);
            }

            // Find sale coupon by ID
            $sale_coupon = SaleCoupon::findOrFail($id);

            // Deactivate sale coupon
            $sale_coupon->is_active = false;
            $sale_coupon->save();

            // Show success message
            Toastr::success(_trans('response.Coupon deleted successfully'), 'Success');
            return response()->json(['status' => 'success']);

        } catch (Exception $e) {
            // Log error
            Log::error($e);

            // Show error message
            Toastr::error(_trans('response.Operation Failed'), 'Error');
            return redirect()->back();
        }
    }

}
