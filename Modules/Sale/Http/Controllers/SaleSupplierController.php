<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleAccount;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Entities\SaleCustomer;
use Modules\Sale\Entities\SalePurchase;
use Modules\Sale\Entities\SaleSupplier;
use Modules\Sale\Entities\SaleCashRegister;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleCustomerGroup;

class SaleSupplierController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] =  _trans('sale.Supplier List');
        $data['search'] = '';
        $data['suppliers'] = SaleSupplier::where('is_active', true);
        if ($search != "") {
            $data['suppliers'] = $data['suppliers']->where('name', 'like', '%' . $search . '%');
        }

        $data['suppliers'] = $data['suppliers']->where('is_active', true)->latest()->paginate($entries);

        return view('sale::product.supplier.index',compact('data'));
    }

    public function clearDue(Request $request)
    {
        $ot_crm_due_purchase_data = SalePurchase::select('id', 'warehouse_id', 'grand_total', 'paid_amount', 'payment_status')
                            ->where([
                                ['payment_status', 1],
                                ['supplier_id', $request->supplier_id]
                            ])->get();
        $total_paid_amount = $request->amount;
        foreach ($ot_crm_due_purchase_data as $key => $purchase_data) {
            if($total_paid_amount == 0)
                break;
            $due_amount = $purchase_data->grand_total - $purchase_data->paid_amount;
            $ot_crm_cash_register_data =  SaleCashRegister::select('id')
                                        ->where([
                                            ['user_id', Auth::id()],
                                            ['warehouse_id', $purchase_data->warehouse_id],
                                            ['status', 1]
                                        ])->first();
            if($ot_crm_cash_register_data)
                $cash_register_id = $ot_crm_cash_register_data->id;
            else
                $cash_register_id = null;
            $account_data = SaleAccount::select('id')->where('is_default', 1)->first();
            if($total_paid_amount >= $due_amount) {
                $paid_amount = $due_amount;
                $payment_status = 2;
            }
            else {
                $paid_amount = $total_paid_amount;
                $payment_status = 1;
            }
            SalePayment::create([
                'payment_reference' => 'ppr-'.date("Ymd").'-'.date("his"),
                'purchase_id' => $purchase_data->id,
                'user_id' => Auth::id(),
                'cash_register_id' => $cash_register_id,
                'account_id' => $account_data->id,
                'amount' => $paid_amount,
                'change' => 0,
                'paying_method' => 'Cash',
                'payment_note' => $request->note
            ]);
            $purchase_data->paid_amount += $paid_amount;
            $purchase_data->payment_status = $payment_status;
            $purchase_data->save();
            $total_paid_amount -= $paid_amount;
        }
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleSupplier.index');
    }

    public function create()
    {
        $data['title'] = _trans('sale.Supplier Create');
        $ot_crm_customer_group_all = SaleCustomerGroup::where('is_active',true)->get();
        return view('sale::product.supplier.create', compact('data','ot_crm_customer_group_all'));
    }

    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'company_name' => [
        //         'max:255',
        //             Rule::unique('suppliers')->where(function ($query) {
        //             return $query->where('is_active', 1);
        //         }),
        //     ],
        //     'email' => [
        //         'max:255',
        //             Rule::unique('suppliers')->where(function ($query) {
        //             return $query->where('is_active', 1);
        //         }),
        //     ],
        //     'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        // ]);

        // //validation for customer if create both user and supplier
        // if(isset($request->both)) {
        //     $this->validate($request, [
        //         'phone_number' => [
        //             'max:255',
        //             Rule::unique('customers')->where(function ($query) {
        //                 return $query->where('is_active', 1);
        //             }),
        //         ],
        //     ]);
        // }
        
        $ot_crm_supplier_data = $request->except('image');
        $ot_crm_supplier_data['is_active'] = true;
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['company_name']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/supplier', $imageName);
            $ot_crm_supplier_data['image'] = $imageName;
        }
        SaleSupplier::create($ot_crm_supplier_data);
        $message = 'Supplier';
        if(isset($request->both)) {
            SaleCustomer::create($ot_crm_supplier_data);
            $message .= ' and Customer';
        }
        // try{
        //     Mail::send( 'mail.supplier_create', $ot_crm_supplier_data, function( $message ) use ($ot_crm_supplier_data)
        //     {
        //         $message->to( $ot_crm_supplier_data['email'] )->subject( 'New Supplier' );
        //     });
        //     $message .= ' created successfully!';
        // }
        // catch(\Exception $e) {
            $message .= ' created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        // }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleSupplier.index');
    }

    public function edit($id)
    {
        $supplier = SaleSupplier::where('id',$id)->first();
        $data['title'] = _trans('sale.Supplier Edit');
        return view('sale::product.supplier.edit',compact('data','supplier'));
        
    }

    public function update(Request $request)
    {

        $input = $request->except('image');
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['company_name']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/supplier', $imageName);
            $input['image'] = $imageName;
        }

        $ot_crm_supplier_data = SaleSupplier::findOrFail($request->id);
        $ot_crm_supplier_data->update($input);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleSupplier.index');
    }

    public function deleteBySelection(Request $request)
    {
        $supplier_id = $request['supplierIdArray'];
        foreach ($supplier_id as $id) {
            $ot_crm_supplier_data = SaleSupplier::findOrFail($id);
            $ot_crm_supplier_data->is_active = false;
            $ot_crm_supplier_data->save();
        }
        return 'Supplier deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_supplier_data = SaleSupplier::findOrFail($id);
        $ot_crm_supplier_data->is_active = false;
        $ot_crm_supplier_data->save();
        Toastr::success(_trans('response.Supplier deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);

    }

    public function importSupplier(Request $request)
    {
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->route('saleSupplier.index')->with('not_permitted', 'Please upload a CSV file');
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

           $supplier = SaleSupplier::firstOrNew(['company_name'=>$data['companyname']]);
           $supplier->name = $data['name'];
           $supplier->image = $data['image'];
           $supplier->vat_number = $data['vatnumber'];
           $supplier->email = $data['email'];
           $supplier->phone_number = $data['phonenumber'];
           $supplier->address = $data['address'];
           $supplier->city = $data['city'];
           $supplier->state = $data['state'];
           $supplier->postal_code = $data['postalcode'];
           $supplier->country = $data['country'];
           $supplier->is_active = true;
           $supplier->save();
           $message = 'Supplier Imported Successfully';
           if($data['email']){
                // try{
                //     Mail::send( 'mail.supplier_create', $data, function( $message ) use ($data)
                //     {
                //         $message->to( $data['email'] )->subject( 'New Supplier' );
                //     });
                // }
                // catch(\Excetion $e){
                    $message = 'Supplier imported successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                // }   
            }
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleSupplier.index');

    }
}
