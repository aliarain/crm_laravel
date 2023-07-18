<?php

namespace Modules\Sale\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Sale\Entities\Sale;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleCustomer;
use Modules\Sale\Entities\SaleDelivery;
use Illuminate\Contracts\Support\Renderable;

class SaleDeliveryController extends Controller
{
    public function index()
    {

        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
        $data['title'] = _trans('common.Delivery List');
        $data['deliveries'] = SaleDelivery::with('user'); 
        if ($search != "") {
            $data['deliveries'] = $data['deliveries']->where('reference_no', 'like', '%' . $search . '%');
        }

        $data['deliveries'] = $data['deliveries']->latest()->paginate($entries);
        return view('sale::sale.delivery.index', compact('data'));
    }
    public function create($id){
        $ot_crm_delivery_data = SaleDelivery::where('sale_id', $id)->first();
        if($ot_crm_delivery_data){
            $customer_sale = DB::table('sales')->join('sale_customers', 'sales.customer_id', '=', 'sale_customers.id')->where('sales.id', $id)->select('sales.reference_no','sale_customers.name')->get();

            $delivery_data[] = $ot_crm_delivery_data->reference_no;
            $delivery_data[] = $customer_sale[0]->reference_no;
            $delivery_data[] = $ot_crm_delivery_data->status;
            $delivery_data[] = $ot_crm_delivery_data->delivered_by;
            $delivery_data[] = $ot_crm_delivery_data->recieved_by;
            $delivery_data[] = $customer_sale[0]->name;
            $delivery_data[] = $ot_crm_delivery_data->address;
            $delivery_data[] = $ot_crm_delivery_data->note;
        }
        else{
            $customer_sale = DB::table('sales')->join('sale_customers', 'sales.customer_id', '=', 'sale_customers.id')->where('sales.id', $id)->select('sales.reference_no','sale_customers.name', 'sale_customers.address', 'sale_customers.city', 'sale_customers.country')->get();

            $delivery_data[] = 'dr-' . date("Ymd") . '-'. date("his");
            $delivery_data[] = $customer_sale[0]->reference_no;
            $delivery_data[] = '';
            $delivery_data[] = '';
            $delivery_data[] = '';
            $delivery_data[] = $customer_sale[0]->name;
            $delivery_data[] = $customer_sale[0]->address.' '.$customer_sale[0]->city.' '.$customer_sale[0]->country;
            $delivery_data[] = '';
        }        
        return $delivery_data;
    }

    public function store(Request $request)
    {
        $data = $request->except('file');
        $delivery = SaleDelivery::firstOrNew(['reference_no' => $data['reference_no'] ]);
        $document = $request->file;
        if ($document) {
            $ext = pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION);
            $documentName = $data['reference_no'] . '.' . $ext;
            $document->move('public/documents/delivery', $documentName);
            $delivery->file = $documentName;
        }
        $delivery->sale_id = $data['sale_id'];
        $delivery->user_id = Auth::id();
        $delivery->address = $data['address'];
        $delivery->delivered_by = $data['delivered_by'];
        $delivery->recieved_by = $data['recieved_by'];
        $delivery->status = $data['status'];
        $delivery->note = $data['note'];
        $delivery->save();
        $ot_crm_sale_data = Sale::find($data['sale_id']);
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
        $message = 'Delivery created successfully';
        if($ot_crm_customer_data->email && $data['status'] != 1){
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['customer'] = $ot_crm_customer_data->name;
            $mail_data['sale_reference'] = $ot_crm_sale_data->reference_no;
            $mail_data['delivery_reference'] = $delivery->reference_no;
            $mail_data['status'] = $data['status'];
            $mail_data['address'] = $data['address'];
            $mail_data['delivered_by'] = $data['delivered_by'];
          
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleDelivery.index');

    }

    public function productDeliveryData($id)
    {
        $ot_crm_delivery_data = SaleDelivery::find($id);
        //return 'madarchod';
        $ot_crm_product_sale_data = Product_Sale::where('sale_id', $ot_crm_delivery_data->sale->id)->get();

        foreach ($ot_crm_product_sale_data as $key => $product_sale_data) {
            $product = Product::select('name', 'code')->find($product_sale_data->product_id);
            if($product_sale_data->variant_id) {
                $ot_crm_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_sale_data->product_id, $product_sale_data->variant_id)->first();
                $product->code = $ot_crm_product_variant_data->item_code;
            }
            if($product_sale_data->product_batch_id) {
                $product_batch_data = ProductBatch::select('batch_no', 'expired_date')->find($product_sale_data->product_batch_id);
                if($product_batch_data) {
                    $batch_no = $product_batch_data->batch_no;
                    $expired_date = date(config('date_format'), strtotime($product_batch_data->expired_date));
                }
            }
            else {
                $batch_no = 'N/A';
                $expired_date = 'N/A';
            }
            $product_sale[0][$key] = $product->code;
            $product_sale[1][$key] = $product->name;
            $product_sale[2][$key] = $batch_no;
            $product_sale[3][$key] = $expired_date;
            $product_sale[4][$key] = $product_sale_data->qty;
        }
        return $product_sale;
    }

    public function sendMail(Request $request)
    {
        $data = $request->all();
        $ot_crm_delivery_data = SaleDelivery::find($data['delivery_id']);
        $ot_crm_sale_data = Sale::find($ot_crm_delivery_data->sale->id);
        $ot_crm_product_sale_data = Product_Sale::where('sale_id', $ot_crm_delivery_data->sale->id)->get();
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
        if($ot_crm_customer_data->email) {
            //collecting male data
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['date'] = date(config('date_format'), strtotime($ot_crm_delivery_data->created_at->toDateString()));
            $mail_data['delivery_reference_no'] = $ot_crm_delivery_data->reference_no;
            $mail_data['sale_reference_no'] = $ot_crm_sale_data->reference_no;
            $mail_data['status'] = $ot_crm_delivery_data->status;
            $mail_data['customer_name'] = $ot_crm_customer_data->name;
            $mail_data['address'] = $ot_crm_customer_data->address . ', '.$ot_crm_customer_data->city;
            $mail_data['phone_number'] = $ot_crm_customer_data->phone_number;
            $mail_data['note'] = $ot_crm_delivery_data->note;
            $mail_data['prepared_by'] = $ot_crm_delivery_data->user->name;
            if($ot_crm_delivery_data->delivered_by)
                $mail_data['delivered_by'] = $ot_crm_delivery_data->delivered_by;
            else
                $mail_data['delivered_by'] = 'N/A';
            if($ot_crm_delivery_data->recieved_by)
                $mail_data['recieved_by'] = $ot_crm_delivery_data->recieved_by;
            else
                $mail_data['recieved_by'] = 'N/A';
            //return $mail_data;

            foreach ($ot_crm_product_sale_data as $key => $product_sale_data) {
                $ot_crm_product_data = Product::select('code', 'name')->find($product_sale_data->product_id);
                $mail_data['codes'][$key] = $ot_crm_product_data->code;
                $mail_data['name'][$key] = $ot_crm_product_data->name;
                if($product_sale_data->variant_id) {
                    $ot_crm_product_variant_data = ProductVariant::select('item_code')->FindExactProduct($product_sale_data->product_id, $product_sale_data->variant_id)->first();
                    $mail_data['codes'][$key] = $ot_crm_product_variant_data->item_code;
                }
                $mail_data['qty'][$key] = $product_sale_data->qty;
            }

            //return $mail_data;

            try{
                Mail::send( 'mail.delivery_challan', $mail_data, function( $message ) use ($mail_data)
                {
                    $message->to( $mail_data['email'] )->subject( 'Delivery Challan' );
                });
                $message = 'Mail sent successfully';
            }
            catch(\Exception $e){
                $message = 'Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        else
            $message = 'Customer does not have email!';
        
        return redirect()->back()->with('message', $message);
    }

    public function edit($id)
    {
        $data = SaleDelivery::find($id);
        $customer_sale = DB::table('sales')->join('sale_customers', 'sales.customer_id', '=', 'sale_customers.id')->where('sales.id', $data->sale_id)->select('sales.reference_no','sale_customers.name')->get();

        $delivery_data = [
            'delivery_id' => $data->id,
            'delivery_reference_no' => $data->reference_no,
            'sale_reference_no' => $data->sale->reference_no,
            'status' => $data->status,
            'delivered_by' => $data->delivered_by,
            'recieved_by' => $data->recieved_by,
            'customer_name' => $data->sale->customer->name,
            'address' => $data->address,
            'note' => $data->note,
        ];

        return $delivery_data;
    }

    public function update(Request $request)
    {
        $input = $request->except('file');
        //return $input;
        $ot_crm_delivery_data = SaleDelivery::find($input['delivery_id']);
        $document = $request->file;
        if ($document) {
            $ext = pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION);
            $documentName = $input['reference_no'] . '.' . $ext;
            $document->move('public/documents/delivery', $documentName);
            $input['file'] = $documentName;
        }
        $ot_crm_delivery_data->update($input);
        $ot_crm_sale_data = Sale::find($ot_crm_delivery_data->sale_id);
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
        $message = 'Delivery updated successfully';
        if($ot_crm_customer_data->email && $input['status'] != 1){
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['customer'] = $ot_crm_customer_data->name;
            $mail_data['sale_reference'] = $ot_crm_sale_data->reference_no;
            $mail_data['delivery_reference'] = $ot_crm_delivery_data->reference_no;
            $mail_data['status'] = $input['status'];
            $mail_data['address'] = $input['address'];
            $mail_data['delivered_by'] = $input['delivered_by'];
            
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleDelivery.index');

    }

    public function deleteBySelection(Request $request)
    {
        $delivery_id = $request['deliveryIdArray'];
        foreach ($delivery_id as $id) {
            $ot_crm_delivery_data = SaleDelivery::find($id);
            $ot_crm_delivery_data->delete();
        }
        return 'Delivery deleted successfully';
    }

    public function destroy($id)
    {
        $ot_crm_delivery_data = SaleDelivery::find($id);
        $ot_crm_delivery_data->delete();
        Toastr::success(_trans('response.Delivery List deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);

    }
}
