<?php

namespace Modules\Sale\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Modules\Sale\Entities\Sale;
use NumberToWords\NumberToWords;
use Illuminate\Routing\Controller;
use Modules\Sale\Entities\SaleTax;
use Modules\Sale\Entities\SaleUnit;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleBrand;
use Modules\Sale\Entities\SaleBiller;
use Modules\Sale\Entities\SaleCoupon;
use Modules\Sale\Entities\SaleAccount;
use Modules\Sale\Entities\SalePayment;
use Modules\Sale\Entities\SaleProduct;
use Modules\Sale\Entities\SaleVariant;
use Modules\Sale\Entities\SaleCategory;
use Modules\Sale\Entities\SaleCustomer;
use Modules\Sale\Entities\SaleDelivery;
use Modules\Sale\Entities\SaleGiftCard;
use Modules\Sale\Entities\SaleWarehouse;
use Modules\Sale\Entities\SalePosSetting;
use Modules\Sale\Entities\SaleProductSale;
use Modules\Sale\Entities\SaleCashRegister;
use Modules\Sale\Entities\SaleProductBatch;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleCustomerGroup;
use Modules\Sale\Entities\SaleProductVariant;
use Modules\Sale\Entities\SaleProductWarehouse;
use Modules\Sale\Entities\SalePaymentWithCheque;
use Modules\Sale\Entities\SaleRewardPointSetting;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;
            
        $ot_crm_gift_card_list = SaleGiftCard::where("is_active", true)->get();
        $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
        $ot_crm_reward_point_setting_data = SaleRewardPointSetting::latest()->first();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_account_list = SaleAccount::where('is_active', true)->get();
        $data['title'] =  _trans('common.Sale List');
        $data['sales'] = Sale::with('biller','customer','supplier','user','warehouse');
        if ($search != "") {
            $data['sales'] = $data['sales']->where('reference_no', 'like', '%' . $search . '%');
        }
        $data['sales'] = $data['sales']->latest()->paginate($entries);
        return view('sale::sale.sale.index',compact( 'data','ot_crm_gift_card_list','ot_crm_pos_setting_data','ot_crm_reward_point_setting_data','ot_crm_account_list','ot_crm_warehouse_list'));
    }

    public function create()
    {
            $ot_crm_customer_list = SaleCustomer::where('is_active', true)->get();

            $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
            $ot_crm_biller_list = SaleBiller::where('is_active', true)->get();
            $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
            $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
            $ot_crm_reward_point_setting_data = SaleRewardPointSetting::latest()->first();
            $data['title'] =  _trans('common.Sale Create');
            return view('sale::sale.sale.create',compact('data','ot_crm_customer_list', 'ot_crm_warehouse_list', 'ot_crm_biller_list', 'ot_crm_pos_setting_data', 'ot_crm_tax_list', 'ot_crm_reward_point_setting_data'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $cash_register_data = SaleCashRegister::where([
            ['user_id', $data['user_id']],
            ['warehouse_id', $data['warehouse_id']],
            ['status', true]
        ])->first();

        if($cash_register_data)
            $data['cash_register_id'] = $cash_register_data->id;

        if(isset($data['created_at']))
            $data['created_at'] = date("Y-m-d H:i:s", strtotime($data['created_at']));
        else
            $data['created_at'] = date("Y-m-d H:i:s");
        if($data['pos']) {
            if(!isset($data['reference_no']))
                $data['reference_no'] = 'posr-' . date("Ymd") . '-'. date("his");

            $balance = $data['grand_total'] - $data['paid_amount'];
            if($balance > 0 || $balance < 0)
                $data['payment_status'] = 2;
            else
                $data['payment_status'] = 4;

            if($data['draft']) {
                $ot_crm_sale_data = Sale::find($data['sale_id']);
                $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $data['sale_id'])->get();
                foreach ($ot_crm_product_sale_data as $product_sale_data) {
                    $product_sale_data->delete();
                }
                $ot_crm_sale_data->delete();
            }
        } else {
            if($data['reference_no'] == null){
                $data['reference_no'] = 'sr-' . date("Ymd") . '-'. date("his");
            }
        }

        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails())
                return redirect()->back()->withErrors($v->errors());

            $documentName = $document->getClientOriginalName();
            $document->move('public/sale/documents', $documentName);
            $data['document'] = $documentName;
        }
        if($data['coupon_active']) {
            $ot_crm_coupon_data = SaleCoupon::find($data['coupon_id']);
            $ot_crm_coupon_data->used += 1;
            $ot_crm_coupon_data->save();
        }

        $ot_crm_sale_data = Sale::create($data);
        $ot_crm_customer_data = SaleCustomer::find($data['customer_id']);
        $ot_crm_reward_point_setting_data = SaleRewardPointSetting::latest()->first();
        //checking if customer gets some points or not
        if($ot_crm_reward_point_setting_data && $ot_crm_reward_point_setting_data->is_active &&  $data['grand_total'] >= $ot_crm_reward_point_setting_data->minimum_amount) {
            $point = (int)($data['grand_total'] / $ot_crm_reward_point_setting_data->per_point_amount);
            $ot_crm_customer_data->points += $point;
            $ot_crm_customer_data->save();
        }
        
        //collecting male data
        $mail_data['email'] = $ot_crm_customer_data->email;
        $mail_data['reference_no'] = $ot_crm_sale_data->reference_no;
        $mail_data['sale_status'] = $ot_crm_sale_data->sale_status;
        $mail_data['payment_status'] = $ot_crm_sale_data->payment_status;
        $mail_data['total_qty'] = $ot_crm_sale_data->total_qty;
        $mail_data['total_price'] = $ot_crm_sale_data->total_price;
        $mail_data['order_tax'] = $ot_crm_sale_data->order_tax;
        $mail_data['order_tax_rate'] = $ot_crm_sale_data->order_tax_rate;
        $mail_data['order_discount'] = $ot_crm_sale_data->order_discount;
        $mail_data['shipping_cost'] = $ot_crm_sale_data->shipping_cost;
        $mail_data['grand_total'] = $ot_crm_sale_data->grand_total;
        $mail_data['paid_amount'] = $ot_crm_sale_data->paid_amount;

        $product_id = $data['product_id'];
        $product_batch_id = $data['product_batch_id'];
        $imei_number = $data['imei_number'];
        $product_code = $data['product_code'];
        $qty = $data['qty'];
        $sale_unit = $data['sale_unit'];
        $net_unit_price = $data['net_unit_price'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $product_sale = [];
        $quantity = 0;

        foreach ($product_id as $i => $id) {
            $ot_crm_product_data = SaleProduct::where('id', $id)->first();
            $product_sale['variant_id'] = null;
            $product_sale['product_batch_id'] = null;
            if($ot_crm_product_data->type == 'combo' && $data['sale_status'] == 1){
                $product_list = explode(",", $ot_crm_product_data->product_list);
                $variant_list = explode(",", $ot_crm_product_data->variant_list);
                if($ot_crm_product_data->variant_list)
                    $variant_list = explode(",", $ot_crm_product_data->variant_list);
                else
                    $variant_list = [];
                $qty_list = explode(",", $ot_crm_product_data->qty_list);
                $price_list = explode(",", $ot_crm_product_data->price_list);
                
                foreach ($product_list as $key=>$child_id) {
                    $child_data = SaleProduct::find($child_id);
                    if(count($variant_list) && $variant_list[$key]) {
                        $child_product_variant_data = SaleProductVariant::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$key]]
                        ])->first();

                        $child_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$key]],
                            ['warehouse_id', $data['warehouse_id'] ],
                        ])->first();

                        $child_product_variant_data->qty -= $qty[$i] * $qty_list[$key];
                        $child_product_variant_data->save();
                    }
                    else {
                        $child_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $child_id],
                            ['warehouse_id', $data['warehouse_id'] ],
                        ])->first();
                    }

                    $child_data->qty -= $qty[$i] * $qty_list[$key];
                    $child_warehouse_data->qty -= $qty[$i] * $qty_list[$key];

                    $child_data->save();
                    $child_warehouse_data->save();
                }
            }

            if($sale_unit[$i] != 'n/a') {
                $ot_crm_sale_unit_data  = SaleUnit::where('unit_name', $sale_unit[$i])->first();
                $sale_unit_id = $ot_crm_sale_unit_data->id;
                if($ot_crm_product_data->is_variant) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($id, $product_code[$i])->first();
                    $product_sale['variant_id'] = $ot_crm_product_variant_data->variant_id;
                }
                if($ot_crm_product_data->is_batch && $product_batch_id[$i]) {
                    $product_sale['product_batch_id'] = $product_batch_id[$i];
                }

                if($data['sale_status'] == 1) {
                    if($ot_crm_sale_unit_data->operator == '*')
                        $quantity = $qty[$i] * $ot_crm_sale_unit_data->operation_value;
                    elseif($ot_crm_sale_unit_data->operator == '/')
                        $quantity = $qty[$i] / $ot_crm_sale_unit_data->operation_value;
                    //deduct quantity
                    $ot_crm_product_data->qty = $ot_crm_product_data->qty - $quantity;
                    $ot_crm_product_data->save();
                    //deduct product variant quantity if exist
                    if($ot_crm_product_data->is_variant) {
                        $ot_crm_product_variant_data->qty -= $quantity;
                        $ot_crm_product_variant_data->save();
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($id, $ot_crm_product_variant_data->variant_id, $data['warehouse_id'])->first();
                    }
                    elseif($product_batch_id[$i]) {
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                            ['product_batch_id', $product_batch_id[$i] ],
                            ['warehouse_id', $data['warehouse_id'] ]
                        ])->first();
                        $ot_crm_product_batch_data = SaleProductBatch::find($product_batch_id[$i]);
                        //deduct product batch quantity
                        $ot_crm_product_batch_data->qty -= $quantity;
                        $ot_crm_product_batch_data->save();
                    }
                    else {
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($id, $data['warehouse_id'])->first();
                    }
                    //deduct quantity from warehouse
                    $ot_crm_product_warehouse_data->qty -= $quantity;
                    $ot_crm_product_warehouse_data->save();
                }
            }
            else
                $sale_unit_id = 0;
            
            if($product_sale['variant_id']) {
                $variant_data = SaleVariant::select('name')->find($product_sale['variant_id']);
                $mail_data['products'][$i] = $ot_crm_product_data->name . ' ['. $variant_data->name .']';
            }
            else
                $mail_data['products'][$i] = $ot_crm_product_data->name;
            //deduct imei number if available
            if($imei_number[$i]) {
                $imei_numbers = explode(",", $imei_number[$i]);
                $all_imei_numbers = explode(",", $ot_crm_product_warehouse_data->imei_number);
                foreach ($imei_numbers as $number) {
                    if (($j = array_search($number, $all_imei_numbers)) !== false) {
                        unset($all_imei_numbers[$j]);
                    }
                }
                $ot_crm_product_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                $ot_crm_product_warehouse_data->save();   
            }
            if($ot_crm_product_data->type == 'digital')
                $mail_data['file'][$i] = url('/public/product/files').'/'.$ot_crm_product_data->file;
            else
                $mail_data['file'][$i] = '';
            if($sale_unit_id)
                $mail_data['unit'][$i] = $ot_crm_sale_unit_data->unit_code;
            else
                $mail_data['unit'][$i] = '';

            $product_sale['sale_id'] = $ot_crm_sale_data->id ;
            $product_sale['product_id'] = $id;
            $product_sale['imei_number'] = $imei_number[$i];
            $product_sale['qty'] = $mail_data['qty'][$i] = $qty[$i];
            $product_sale['sale_unit_id'] = $sale_unit_id;
            $product_sale['net_unit_price'] = $net_unit_price[$i];
            $product_sale['discount'] = $discount[$i];
            $product_sale['tax_rate'] = $tax_rate[$i];
            $product_sale['tax'] = $tax[$i];
            $product_sale['total'] = $mail_data['total'][$i] = $total[$i];
            SaleProductSale::create($product_sale);
        }
        if($data['sale_status'] == 3)
            $message = 'Sale successfully added to draft';
        else
            $message = ' Sale created successfully';
        if($mail_data['email'] && $data['sale_status'] == 1) {
           
                $message = ' Sale created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        }

        if($data['payment_status'] == 3 || $data['payment_status'] == 4 || ($data['payment_status'] == 2 && $data['pos'] && $data['paid_amount'] > 0)) {
            
            $ot_crm_payment_data = new SalePayment();
            $ot_crm_payment_data->user_id = Auth::id();

            if($data['paid_by_id'] == 1)
                $paying_method = 'Cash';
            elseif ($data['paid_by_id'] == 2) {
                $paying_method = 'Gift Card';
            }
            elseif ($data['paid_by_id'] == 3)
                $paying_method = 'Credit Card';
            elseif ($data['paid_by_id'] == 4)
                $paying_method = 'Cheque';
            elseif ($data['paid_by_id'] == 5)
                $paying_method = 'Paypal';
            elseif($data['paid_by_id'] == 6)
                $paying_method = 'Deposit';
            elseif($data['paid_by_id'] == 7) {
                $paying_method = 'Points';
                $ot_crm_payment_data->used_points = $data['used_points'];
            }

            if($cash_register_data)
                $ot_crm_payment_data->cash_register_id = $cash_register_data->id;
            $ot_crm_account_data = SaleAccount::where('is_default', true)->first();
            $ot_crm_payment_data->account_id = $ot_crm_account_data->id;
            $ot_crm_payment_data->sale_id = $ot_crm_sale_data->id;
            $data['payment_reference'] = 'spr-'.date("Ymd").'-'.date("his");
            $ot_crm_payment_data->payment_reference = $data['payment_reference'];
            $ot_crm_payment_data->amount = $data['paid_amount'];
            $ot_crm_payment_data->change = $data['paying_amount'] - $data['paid_amount'];
            $ot_crm_payment_data->paying_method = $paying_method;
            $ot_crm_payment_data->payment_note = $data['payment_note'];
            $ot_crm_payment_data->save();

            $ot_crm_payment_data = SalePayment::latest()->first();
            $data['payment_id'] = $ot_crm_payment_data->id;
            if($paying_method == 'Credit Card'){
                $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
                Stripe::setApiKey($ot_crm_pos_setting_data->stripe_secret_key);
                $token = $data['stripeToken'];
                $grand_total = $data['grand_total'];

                $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $data['customer_id'])->first();

                if(!$ot_crm_payment_with_credit_card_data) {
                    // Create a Customer:
                    $customer = \Stripe\Customer::create([
                        'source' => $token
                    ]);
                    
                    // Charge the Customer instead of the card:
                    $charge = \Stripe\Charge::create([
                        'amount' => $grand_total * 100,
                        'currency' => 'usd',
                        'customer' => $customer->id
                    ]);
                    $data['customer_stripe_id'] = $customer->id;
                }
                else {
                    $customer_id = 
                    $ot_crm_payment_with_credit_card_data->customer_stripe_id;

                    $charge = \Stripe\Charge::create([
                        'amount' => $grand_total * 100,
                        'currency' => 'usd',
                        'customer' => $customer_id, // Previously stored, then retrieved
                    ]);
                    $data['customer_stripe_id'] = $customer_id;
                }
                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            }
            elseif ($paying_method == 'Gift Card') {
                $ot_crm_gift_card_data = SaleGiftCard::find($data['gift_card_id']);
                $ot_crm_gift_card_data->expense += $data['paid_amount'];
                $ot_crm_gift_card_data->save();
                PaymentWithGiftCard::create($data);
            }
            elseif ($paying_method == 'Cheque') {
                SalePaymentWithCheque::create($data);
            }
            elseif ($paying_method == 'Paypal') {
                $provider = new ExpressCheckout;
                $paypal_data = [];
                $paypal_data['items'] = [];
                foreach ($data['product_id'] as $key => $product_id) {
                    $ot_crm_product_data = SaleProduct::find($product_id);
                    $paypal_data['items'][] = [
                        'name' => $ot_crm_product_data->name,
                        'price' => ($data['subtotal'][$key]/$data['qty'][$key]),
                        'qty' => $data['qty'][$key]
                    ];
                }
                $paypal_data['items'][] = [
                    'name' => 'Order Tax',
                    'price' => $data['order_tax'],
                    'qty' => 1
                ];
                $paypal_data['items'][] = [
                    'name' => 'Order Discount',
                    'price' => $data['order_discount'] * (-1),
                    'qty' => 1
                ];
                $paypal_data['items'][] = [
                    'name' => 'Shipping Cost',
                    'price' => $data['shipping_cost'],
                    'qty' => 1
                ];
                if($data['grand_total'] != $data['paid_amount']){
                    $paypal_data['items'][] = [
                        'name' => 'Due',
                        'price' => ($data['grand_total'] - $data['paid_amount']) * (-1),
                        'qty' => 1
                    ];
                }
                //return $paypal_data;
                $paypal_data['invoice_id'] = $ot_crm_sale_data->reference_no;
                $paypal_data['invoice_description'] = "Reference # {$paypal_data['invoice_id']} Invoice";
                $paypal_data['return_url'] = url('/sale/paypalSuccess');
                $paypal_data['cancel_url'] = url('/sale/create');

                $total = 0;
                foreach($paypal_data['items'] as $item) {
                    $total += $item['price']*$item['qty'];
                }

                $paypal_data['total'] = $total;
                $response = $provider->setExpressCheckout($paypal_data);
                 // This will redirect user to PayPal
                return redirect($response['paypal_link']);
            }
            elseif($paying_method == 'Deposit'){
                $ot_crm_customer_data->expense += $data['paid_amount'];
                $ot_crm_customer_data->save();
            }
            elseif($paying_method == 'Points'){
                $ot_crm_customer_data->points -= $data['used_points'];
                $ot_crm_customer_data->save();
            }
        }


        if($ot_crm_sale_data->sale_status == '1'){
            return redirect('sale/sale/gen_invoice/' . $ot_crm_sale_data->id)->with('message', $message);
        }elseif($data['pos']){
            Toastr::success( $message, 'Success');
            return redirect()->route('saleSale.pos');
        }else{
            Toastr::success( $message, 'Success');
            return redirect()->route('saleSale.index');
        }

    }

    public function sendMail(Request $request)
    {
        $data = $request->all();
        $ot_crm_sale_data = Sale::find($data['sale_id']);
        $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $data['sale_id'])->get();
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
        if($ot_crm_customer_data->email) {
            //collecting male data
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['reference_no'] = $ot_crm_sale_data->reference_no;
            $mail_data['sale_status'] = $ot_crm_sale_data->sale_status;
            $mail_data['payment_status'] = $ot_crm_sale_data->payment_status;
            $mail_data['total_qty'] = $ot_crm_sale_data->total_qty;
            $mail_data['total_price'] = $ot_crm_sale_data->total_price;
            $mail_data['order_tax'] = $ot_crm_sale_data->order_tax;
            $mail_data['order_tax_rate'] = $ot_crm_sale_data->order_tax_rate;
            $mail_data['order_discount'] = $ot_crm_sale_data->order_discount;
            $mail_data['shipping_cost'] = $ot_crm_sale_data->shipping_cost;
            $mail_data['grand_total'] = $ot_crm_sale_data->grand_total;
            $mail_data['paid_amount'] = $ot_crm_sale_data->paid_amount;

            foreach ($ot_crm_product_sale_data as $key => $product_sale_data) {
                $ot_crm_product_data = SaleProduct::find($product_sale_data->product_id);
                if($product_sale_data->variant_id) {
                    $variant_data = SaleVariant::select('name')->find($product_sale_data->variant_id);
                    $mail_data['products'][$key] = $ot_crm_product_data->name . ' [' . $variant_data->name . ']';
                }
                else
                    $mail_data['products'][$key] = $ot_crm_product_data->name;
                if($ot_crm_product_data->type == 'digital')
                    $mail_data['file'][$key] = url('/public/product/files').'/'.$ot_crm_product_data->file;
                else
                    $mail_data['file'][$key] = '';
                if($product_sale_data->sale_unit_id){
                    $ot_crm_unit_data = SaleUnit::find($product_sale_data->sale_unit_id);
                    $mail_data['unit'][$key] = $ot_crm_unit_data->unit_code;
                }
                else
                    $mail_data['unit'][$key] = '';

                $mail_data['qty'][$key] = $product_sale_data->qty;
                $mail_data['total'][$key] = $product_sale_data->qty;
            }

            try{
                Mail::send( 'mail.sale_details', $mail_data, function( $message ) use ($mail_data)
                {
                    $message->to( $mail_data['email'] )->subject( 'Sale Details' );
                });
                $message = 'Mail sent successfully';
            }
            catch(\Exception $e){
                $message = 'Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        else
            $message = 'Customer doesnt have email!';
        
        return redirect()->back()->with('message', $message);
    }

    public function paypalSuccess(Request $request)
    {
        $ot_crm_sale_data = Sale::latest()->first();
        $ot_crm_payment_data = SalePayment::latest()->first();
        $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $ot_crm_sale_data->id)->get();
        $provider = new ExpressCheckout;
        $token = $request->token;
        $payerID = $request->PayerID;
        $paypal_data['items'] = [];
        foreach ($ot_crm_product_sale_data as $key => $product_sale_data) {
            $ot_crm_product_data = SaleProduct::find($product_sale_data->product_id);
            $paypal_data['items'][] = [
                'name' => $ot_crm_product_data->name,
                'price' => ($product_sale_data->total/$product_sale_data->qty),
                'qty' => $product_sale_data->qty
            ];
        }
        $paypal_data['items'][] = [
            'name' => 'order tax',
            'price' => $ot_crm_sale_data->order_tax,
            'qty' => 1
        ];
        $paypal_data['items'][] = [
            'name' => 'order discount',
            'price' => $ot_crm_sale_data->order_discount * (-1),
            'qty' => 1
        ];
        $paypal_data['items'][] = [
            'name' => 'shipping cost',
            'price' => $ot_crm_sale_data->shipping_cost,
            'qty' => 1
        ];
        if($ot_crm_sale_data->grand_total != $ot_crm_sale_data->paid_amount){
            $paypal_data['items'][] = [
                'name' => 'Due',
                'price' => ($ot_crm_sale_data->grand_total - $ot_crm_sale_data->paid_amount) * (-1),
                'qty' => 1
            ];
        }

        $paypal_data['invoice_id'] = $ot_crm_payment_data->payment_reference;
        $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
        $paypal_data['return_url'] = url('/sale/paypalSuccess');
        $paypal_data['cancel_url'] = url('/sale/create');

        $total = 0;
        foreach($paypal_data['items'] as $item) {
            $total += $item['price']*$item['qty'];
        }

        $paypal_data['total'] = $ot_crm_sale_data->paid_amount;
        $response = $provider->getExpressCheckoutDetails($token);
        $response = $provider->doExpressCheckoutPayment($paypal_data, $token, $payerID);
        $data['payment_id'] = $ot_crm_payment_data->id;
        $data['transaction_id'] = $response['PAYMENTINFO_0_TRANSACTIONID'];
        PaymentWithPaypal::create($data);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleSale.index');

    }

    public function paypalPaymentSuccess(Request $request, $id)
    {
        $ot_crm_payment_data = SalePayment::find($id);
        $provider = new ExpressCheckout;
        $token = $request->token;
        $payerID = $request->PayerID;
        $paypal_data['items'] = [];
        $paypal_data['items'][] = [
            'name' => 'Paid Amount',
            'price' => $ot_crm_payment_data->amount,
            'qty' => 1
        ];
        $paypal_data['invoice_id'] = $ot_crm_payment_data->payment_reference;
        $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
        $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess');
        $paypal_data['cancel_url'] = url('/sale');

        $total = 0;
        foreach($paypal_data['items'] as $item) {
            $total += $item['price']*$item['qty'];
        }

        $paypal_data['total'] = $total;
        $response = $provider->getExpressCheckoutDetails($token);
        $response = $provider->doExpressCheckoutPayment($paypal_data, $token, $payerID);
        $data['payment_id'] = $ot_crm_payment_data->id;
        $data['transaction_id'] = $response['PAYMENTINFO_0_TRANSACTIONID'];
        PaymentWithPaypal::create($data);
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleSale.index');
    }

    public function getProduct($id)
    {
        $ot_crm_product_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
        ->where([
            ['sale_products.is_active', true],
            ['sale_product_warehouses.warehouse_id', $id],
            ['sale_product_warehouses.qty', '>', 0]
        ])
        ->whereNull('sale_product_warehouses.variant_id')
        ->whereNull('sale_product_warehouses.product_batch_id')
        ->select('sale_product_warehouses.*',  'sale_products.is_embeded')
        ->get();

        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect(); //important as the existing connection if any would be in strict mode

        $ot_crm_product_with_batch_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
        ->where([
            ['sale_products.is_active', true],
            ['sale_product_warehouses.warehouse_id', $id],
            ['sale_product_warehouses.qty', '>', 0]
        ])
        ->whereNull('sale_product_warehouses.variant_id')
        ->whereNotNull('sale_product_warehouses.product_batch_id')
        ->select('sale_product_warehouses.*', 'sale_products.is_embeded')
        ->groupBy('sale_product_warehouses.product_id')
        ->get();

        //now changing back the strict ON
        config()->set('database.connections.mysql.strict', true);
        DB::reconnect();

        $ot_crm_product_with_variant_warehouse_data = SaleProduct::join('sale_product_warehouses', 'sale_products.id', '=', 'sale_product_warehouses.product_id')
        ->where([
            ['sale_products.is_active', true],
            ['sale_product_warehouses.warehouse_id', $id],
            ['sale_product_warehouses.qty', '>', 0]
        ])
        ->whereNotNull('sale_product_warehouses.variant_id')
        ->select('sale_product_warehouses.*', 'sale_products.is_embeded')
        ->get();
        
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_type = [];
        $product_id = [];
        $product_list = [];
        $qty_list = [];
        $product_price = [];
        $batch_no = [];
        $product_batch_id = [];
        $expired_date = [];
        $is_embeded = [];
        //product without variant
        foreach ($ot_crm_product_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $product_price[] = $product_warehouse->price;
            $ot_crm_product_data = SaleProduct::find($product_warehouse->product_id);
            $product_code[] =  $ot_crm_product_data->code;
            $product_name[] = htmlspecialchars($ot_crm_product_data->name);
            $product_type[] = $ot_crm_product_data->type;
            $product_id[] = $ot_crm_product_data->id;
            $product_list[] = $ot_crm_product_data->product_list;
            $qty_list[] = $ot_crm_product_data->qty_list;
            $batch_no[] = null;
            $product_batch_id[] = null;
            $expired_date[] = null;
            if($product_warehouse->is_embeded)
                $is_embeded[] = $product_warehouse->is_embeded;
            else
                $is_embeded[] = 0;
        }
        //product with batches
        foreach ($ot_crm_product_with_batch_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $product_price[] = $product_warehouse->price;
            $ot_crm_product_data = SaleProduct::find($product_warehouse->product_id);
            $product_code[] =  $ot_crm_product_data->code;
            $product_name[] = htmlspecialchars($ot_crm_product_data->name);
            $product_type[] = $ot_crm_product_data->type;
            $product_id[] = $ot_crm_product_data->id;
            $product_list[] = $ot_crm_product_data->product_list;
            $qty_list[] = $ot_crm_product_data->qty_list;
            $product_batch_data = SaleProductBatch::select('id', 'batch_no', 'expired_date')->find($product_warehouse->product_batch_id);
            $batch_no[] = $product_batch_data->batch_no;
            $product_batch_id[] = $product_batch_data->id;
            $expired_date[] = date("Y-m-d", strtotime($product_batch_data->expired_date));
            if($product_warehouse->is_embeded)
                $is_embeded[] = $product_warehouse->is_embeded;
            else
                $is_embeded[] = 0;
        }
        //product with variant
        foreach ($ot_crm_product_with_variant_warehouse_data as $product_warehouse) 
        {
            $product_qty[] = $product_warehouse->qty;
            $ot_crm_product_data = SaleProduct::find($product_warehouse->product_id);
            $ot_crm_product_variant_data = SaleProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
            $product_code[] =  $ot_crm_product_variant_data->item_code;
            $product_name[] = htmlspecialchars($ot_crm_product_data->name);
            $product_type[] = $ot_crm_product_data->type;
            $product_id[] = $ot_crm_product_data->id;
            $product_list[] = $ot_crm_product_data->product_list;
            $qty_list[] = $ot_crm_product_data->qty_list;
            $batch_no[] = null;
            $product_batch_id[] = null;
            $expired_date[] = null;
            if($product_warehouse->is_embeded)
                $is_embeded[] = $product_warehouse->is_embeded;
            else
                $is_embeded[] = 0;
        }
        //retrieve product with type of digital, combo and service
        $ot_crm_product_data = SaleProduct::whereNotIn('type', ['standard'])->where('is_active', true)->get();
        foreach ($ot_crm_product_data as $product) 
        {
            $product_qty[] = $product->qty;
            $product_code[] =  $product->code;
            $product_name[] = $product->name;
            $product_type[] = $product->type;
            $product_id[] = $product->id;
            $product_list[] = $product->product_list;
            $qty_list[] = $product->qty_list;
            $batch_no[] = null;
            $product_batch_id[] = null;
            $expired_date[] = null;
            if($product_warehouse->is_embeded)
                $is_embeded[] = $product->is_embeded;
            else
                $is_embeded[] = 0;
        }
        $product_data = [$product_code, $product_name, $product_qty, $product_type, $product_id, $product_list, $qty_list, $product_price, $batch_no, $product_batch_id, $expired_date, $is_embeded];
        return $product_data;
    }

    public function posSale()
    {
            $ot_crm_customer_list = SaleCustomer::where('is_active', true)->get();
            $ot_crm_customer_group_all = SaleCustomerGroup::where('is_active', true)->get();
            $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
            $ot_crm_biller_list = SaleBiller::where('is_active', true)->get();
            $ot_crm_reward_point_setting_data = SaleRewardPointSetting::latest()->first();
            $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
            $ot_crm_product_list = SaleProduct::select('id', 'name', 'code', 'image')->ActiveFeatured()->whereNull('is_variant')->get();
            foreach ($ot_crm_product_list as $key => $product) {
                $images = explode(",", $product->image);
                $product->base_image = $images[0];
            }
            $ot_crm_product_list_with_variant = SaleProduct::select('id', 'name', 'code', 'image')->ActiveFeatured()->whereNotNull('is_variant')->get();

            foreach ($ot_crm_product_list_with_variant as $product) {
                $images = explode(",", $product->image);
                $product->base_image = $images[0];
                $ot_crm_product_variant_data = $product->variant()->orderBy('position')->get();
                $main_name = $product->name;
                $temp_arr = [];
                foreach ($ot_crm_product_variant_data as $key => $variant) {
                    $product->name = $main_name.' ['.$variant->name.']';
                    $product->code = $variant->pivot['item_code'];
                    $ot_crm_product_list[] = clone($product);
                }
            }
            
            $product_number = count($ot_crm_product_list);
            $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
            $ot_crm_brand_list = SaleBrand::where('is_active',true)->get();
            $ot_crm_category_list = SaleCategory::where('is_active',true)->get();
            $recent_sale = Sale::where('sale_status', 1)->orderBy('id', 'desc')->take(10)->get();
            $recent_draft = Sale::where('sale_status', 3)->orderBy('id', 'desc')->take(10)->get();
            $ot_crm_coupon_list = SaleCoupon::where('is_active',true)->get();
            $flag = 0;

            return view('sale::sale.sale.pos', compact('ot_crm_customer_list', 'ot_crm_customer_group_all', 'ot_crm_warehouse_list', 'ot_crm_reward_point_setting_data', 'ot_crm_product_list', 'product_number', 'ot_crm_tax_list', 'ot_crm_biller_list', 'ot_crm_pos_setting_data', 'ot_crm_brand_list', 'ot_crm_category_list', 'recent_sale', 'recent_draft', 'ot_crm_coupon_list', 'flag'));
    }

    public function getProductByFilter($category_id, $brand_id)
    {
        $data = [];
        if(($category_id != 0) && ($brand_id != 0)){
            $ot_crm_product_list = DB::table('sale_products')
                                ->join('sale_categories', 'sale_products.category_id', '=', 'sale_categories.id')
                                ->where([
                                    ['sale_products.is_active', true],
                                    ['sale_products.category_id', $category_id],
                                    ['brand_id', $brand_id]
                                ])->orWhere([
                                    ['sale_categories.parent_id', $category_id],
                                    ['sale_products.is_active', true],
                                    ['brand_id', $brand_id]
                                ])->select('sale_products.name', 'sale_products.code', 'sale_products.image')->get();
        }
        elseif(($category_id != 0) && ($brand_id == 0)){
            $ot_crm_product_list = DB::table('sale_products')
                                ->join('sale_categories', 'sale_products.category_id', '=', 'sale_categories.id')
                                ->where([
                                    ['sale_products.is_active', true],
                                    ['sale_products.category_id', $category_id],
                                ])->orWhere([
                                    ['sale_categories.parent_id', $category_id],
                                    ['sale_products.is_active', true]
                                ])->select('sale_products.id', 'sale_products.name', 'sale_products.code', 'sale_products.image', 'sale_products.is_variant')->get();
        }
        elseif(($category_id == 0) && ($brand_id != 0)){
            $ot_crm_product_list = SaleProduct::where([
                                ['brand_id', $brand_id],
                                ['is_active', true]
                            ])
                            ->select('sale_products.id', 'sale_products.name', 'sale_products.code', 'sale_products.image', 'sale_products.is_variant')
                            ->get();
        }
        else
            $ot_crm_product_list = SaleProduct::where('is_active', true)->get();

        $index = 0;
        foreach ($ot_crm_product_list as $product) {
            if($product->is_variant) {
                $ot_crm_product_data = SaleProduct::select('id')->find($product->id);
                $ot_crm_product_variant_data = $ot_crm_product_data->variant()->orderBy('position')->get();
                foreach ($ot_crm_product_variant_data as $key => $variant) {
                    $data['name'][$index] = $product->name.' ['.$variant->name.']';
                    $data['code'][$index] = $variant->pivot['item_code'];
                    $images = explode(",", $product->image);
                    $data['image'][$index] = asset('public/images/product/'.$images[0]);
                    $index++;
                }
            }
            else {
                $data['name'][$index] = $product->name;
                $data['code'][$index] = $product->code;
                $images = explode(",", $product->image);
                $data['image'][$index] = asset('public/images/product/'.$images[0]);
                $index++;
            }
        }
        return $data;
    }

    public function getFeatured()
    {
        $data = [];
        $ot_crm_product_list = SaleProduct::where([
            ['is_active', true],
            ['featured', true]
        ])->select('sale_products.id', 'sale_products.name', 'sale_products.code', 'sale_products.image', 'sale_products.is_variant')->get();

        $index = 0;
        foreach ($ot_crm_product_list as $product) {
            if($product->is_variant) {
                $ot_crm_product_data = SaleProduct::select('id')->find($product->id);
                $ot_crm_product_variant_data = $ot_crm_product_data->variant()->orderBy('position')->get();
                foreach ($ot_crm_product_variant_data as $key => $variant) {
                    $data['name'][$index] = $product->name.' ['.$variant->name.']';
                    $data['code'][$index] = $variant->pivot['item_code'];
                    $images = explode(",", $product->image);
                    $data['image'][$index] =  asset('public/images/product/'.$images[0]);
                    $index++;
                }
            }
            else {
                $data['name'][$index] = $product->name;
                $data['code'][$index] = $product->code;
                $images = explode(",", $product->image);
                $data['image'][$index] = asset('public/images/product/'.$images[0]);
                $index++;
            }
        }
        return $data;
    }

    public function getCustomerGroup($id)
    {
         $ot_crm_customer_data = SaleCustomer::find($id);
         $ot_crm_customer_group_data = SaleCustomerGroup::find($ot_crm_customer_data->customer_group_id);
         return $ot_crm_customer_group_data->percentage;
    }

    public function ot_crmProductSearch(Request $request)
    {
        $todayDate = date('Y-m-d');
        $product_code = explode("(", $request['data']);
        $product_info = explode("?", $request['data']);
        $customer_id = $product_info[1];
        if(strpos($request['data'], '|')) {
            $product_info = explode("|", $request['data']);
            $embeded_code = $product_code[0];

            $product_code[0] = substr($embeded_code, 0, 15);
            // dd($product_code[0]);
            $qty = substr($embeded_code, 7, 5) / 1000;
        }
        else {
            $product_code[0] = rtrim($product_code[0], " ");
            $qty = $product_info[2];
        }
        $product_variant_id = null;
        $all_discount = DB::table('sale_discount_plan_customers')
                        ->join('sale_discount_plans', 'sale_discount_plans.id', '=', 'sale_discount_plan_customers.discount_plan_id')
                        ->join('sale_discount_plan_discounts', 'sale_discount_plans.id', '=', 'sale_discount_plan_discounts.discount_plan_id')
                        ->join('sale_discounts', 'sale_discounts.id', '=', 'sale_discount_plan_discounts.discount_id')
                        ->where([
                            ['sale_discount_plans.is_active', true],
                            ['sale_discounts.is_active', true],
                            ['sale_discount_plan_customers.customer_id', $customer_id]
                        ])
                        ->select('sale_discounts.*')
                        ->get();
        $ot_crm_product_data = SaleProduct::where([
            ['code', $product_code[0]],
            ['is_active', true]
        ])->first();
        if(!$ot_crm_product_data) {
            $ot_crm_product_data = SaleProduct::join('sale_product_variants', 'sale_products.id', 'sale_product_variants.product_id')
                ->select('sale_products.*', 'sale_product_variants.id as product_variant_id', 'sale_product_variants.item_code', 'sale_product_variants.additional_price')
                ->where([
                    ['sale_product_variants.item_code', $product_code[0]],
                    ['sale_products.is_active', true]
                ])->first();
            $product_variant_id = $ot_crm_product_data->product_variant_id;
        }

        $product[] = $ot_crm_product_data->name;
        if($ot_crm_product_data->is_variant){
            $product[] = $ot_crm_product_data->item_code;
            $ot_crm_product_data->price += $ot_crm_product_data->additional_price;
        }
        else
            $product[] = $ot_crm_product_data->code;

        $no_discount = 1;
        foreach ($all_discount as $key => $discount) {
            $product_list = explode(",", $discount->product_list);
            $days = explode(",", $discount->days);

            if( ( $discount->applicable_for == 'All' || in_array($ot_crm_product_data->id, $product_list) ) && ( $todayDate >= $discount->valid_from && $todayDate <= $discount->valid_till && in_array(date('D'), $days) && $qty >= $discount->minimum_qty && $qty <= $discount->maximum_qty ) ) {
                if($discount->type == 'flat') {
                    $product[] = $ot_crm_product_data->price - $discount->value;
                }
                elseif($discount->type == 'percentage') {
                    $product[] = $ot_crm_product_data->price - ($ot_crm_product_data->price * ($discount->value/100));
                }
                $no_discount = 0;
                break;
            }
            else {
                continue;
            }       
        }

        if($ot_crm_product_data->promotion && $todayDate <= $ot_crm_product_data->last_date && $no_discount) {
            $product[] = $ot_crm_product_data->promotion_price;
        }
        elseif($no_discount)
            $product[] = $ot_crm_product_data->price;
        
        if($ot_crm_product_data->tax_id) {
            $ot_crm_tax_data = SaleTax::find($ot_crm_product_data->tax_id);
            $product[] = $ot_crm_tax_data->rate;
            $product[] = $ot_crm_tax_data->name;
        }
        else{
            $product[] = 0;
            $product[] = 'No Tax';
        }
        $product[] = $ot_crm_product_data->tax_method;
        if($ot_crm_product_data->type == 'standard'){
            $units = SaleUnit::where("base_unit", $ot_crm_product_data->unit_id)
                    ->orWhere('id', $ot_crm_product_data->unit_id)
                    ->get();
            $unit_name = array();
            $unit_operator = array();
            $unit_operation_value = array();
            foreach ($units as $unit) {
                if($ot_crm_product_data->sale_unit_id == $unit->id) {
                    array_unshift($unit_name, $unit->unit_name);
                    array_unshift($unit_operator, $unit->operator);
                    array_unshift($unit_operation_value, $unit->operation_value);
                }
                else {
                    $unit_name[]  = $unit->unit_name;
                    $unit_operator[] = $unit->operator;
                    $unit_operation_value[] = $unit->operation_value;
                }
            }
            $product[] = implode(",",$unit_name) . ',';
            $product[] = implode(",",$unit_operator) . ',';
            $product[] = implode(",",$unit_operation_value) . ',';     
        }
        else{
            $product[] = 'n/a'. ',';
            $product[] = 'n/a'. ',';
            $product[] = 'n/a'. ',';
        }
        $product[] = $ot_crm_product_data->id;
        $product[] = $product_variant_id;
        $product[] = $ot_crm_product_data->promotion;
        $product[] = $ot_crm_product_data->is_batch;
        $product[] = $ot_crm_product_data->is_imei;
        $product[] = $ot_crm_product_data->is_variant;
        $product[] = $qty;

        return $product;

    }

    public function checkDiscount(Request $request)
    {
        $qty = $request->input('qty');
        $customer_id = $request->input('customer_id');
        $ot_crm_product_data = SaleProduct::select('id', 'price', 'promotion', 'promotion_price', 'last_date')->find($request->input('product_id'));
        $todayDate = date('Y-m-d');
        $all_discount = DB::table('sale_discount_plan_customers')
                        ->join('sale_discount_plans', 'sale_discount_plans.id', '=', 'sale_discount_plan_customers.discount_plan_id')
                        ->join('sale_discount_plan_discounts', 'sale_discount_plans.id', '=', 'sale_discount_plan_discounts.discount_plan_id')
                        ->join('sale_discounts', 'sale_discounts.id', '=', 'sale_discount_plan_discounts.discount_id')
                        ->where([
                            ['sale_discount_plans.is_active', true],
                            ['sale_discounts.is_active', true],
                            ['sale_discount_plan_customers.customer_id', $customer_id]
                        ])
                        ->select('sale_discounts.*')
                        ->get();
        $no_discount = 1;
        foreach ($all_discount as $key => $discount) {
            $product_list = explode(",", $discount->product_list);
            $days = explode(",", $discount->days);

            if( ( $discount->applicable_for == 'All' || in_array($ot_crm_product_data->id, $product_list) ) && ( $todayDate >= $discount->valid_from && $todayDate <= $discount->valid_till && in_array(date('D'), $days) && $qty >= $discount->minimum_qty && $qty <= $discount->maximum_qty ) ) {
                if($discount->type == 'flat') {
                    $price = $ot_crm_product_data->price - $discount->value;
                }
                elseif($discount->type == 'percentage') {
                    $price = $ot_crm_product_data->price - ($ot_crm_product_data->price * ($discount->value/100));
                }
                $no_discount = 0;
                break;
            }
            else {
                continue;
            }       
        }

        if($ot_crm_product_data->promotion && $todayDate <= $ot_crm_product_data->last_date && $no_discount) {
            $price = $ot_crm_product_data->promotion_price;
        }
        elseif($no_discount)
            $price = $ot_crm_product_data->price;
        
        $data = [$price, $ot_crm_product_data->promotion];
        return $data;
    }

    public function getGiftCard()
    {
        $gift_card = SaleGiftCard::where("is_active", true)->whereDate('expired_date', '>=', date("Y-m-d"))->get(['id', 'card_no', 'amount', 'expense']);
        return json_encode($gift_card);
    }

    public function productSaleData($id)
    {
        $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $id)->get();
        foreach ($ot_crm_product_sale_data as $key => $product_sale_data) {
            $product = SaleProduct::find($product_sale_data->product_id);
            if($product_sale_data->variant_id) {
                $ot_crm_product_variant_data = SaleProductVariant::select('item_code')->FindExactProduct($product_sale_data->product_id, $product_sale_data->variant_id)->first();
                $product->code = $ot_crm_product_variant_data->item_code;
            }
            $unit_data = SaleUnit::find($product_sale_data->sale_unit_id);
            if($unit_data){
                $unit = $unit_data->unit_code;
            }
            else
                $unit = '';
            if($product_sale_data->product_batch_id) {
                $product_batch_data = SaleProductBatch::select('batch_no')->find($product_sale_data->product_batch_id);
                $product_sale[7][$key] = $product_batch_data->batch_no;
            }
            else
                $product_sale[7][$key] = 'N/A';
            $product_sale[0][$key] = $product->name . ' [' . $product->code . ']';
            if($product_sale_data->imei_number)
                $product_sale[0][$key] .= '<br>IMEI or Serial Number: '. $product_sale_data->imei_number;
            $product_sale[1][$key] = $product_sale_data->qty;
            $product_sale[2][$key] = $unit;
            $product_sale[3][$key] = $product_sale_data->tax;
            $product_sale[4][$key] = $product_sale_data->tax_rate;
            $product_sale[5][$key] = $product_sale_data->discount;
            $product_sale[6][$key] = $product_sale_data->total;
        }
        return $product_sale;
    }

    public function saleByCsv()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('sales-add')){
            $ot_crm_customer_list = SaleCustomer::where('is_active', true)->get();
            $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
            $ot_crm_biller_list = SaleBiller::where('is_active', true)->get();
            $ot_crm_tax_list = SaleTax::where('is_active', true)->get();

            return view('backend.sale.import',compact('ot_crm_customer_list', 'ot_crm_warehouse_list', 'ot_crm_biller_list', 'ot_crm_tax_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function importSale(Request $request)
    {
        //get the file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        //checking if this is a CSV file
        if($ext != 'csv')
            return redirect()->back()->with('message', 'Please upload a CSV file');

        $filePath=$upload->getRealPath();
        $file_handle = fopen($filePath, 'r');
        $i = 0;
        //validate the file
        while (!feof($file_handle) ) {
            $current_line = fgetcsv($file_handle);
            if($current_line && $i > 0){
                $product_data[] = SaleProduct::where('code', $current_line[0])->first();
                if(!$product_data[$i-1])
                    return redirect()->back()->with('message', 'Product does not exist!');
                $unit[] = SaleUnit::where('unit_code', $current_line[2])->first();
                if(!$unit[$i-1] && $current_line[2] == 'n/a')
                    $unit[$i-1] = 'n/a';
                elseif(!$unit[$i-1]){
                    return redirect()->back()->with('message', 'Sale unit does not exist!');
                }
                if(strtolower($current_line[5]) != "no tax"){
                    $tax[] = SaleTax::where('name', $current_line[5])->first();
                    if(!$tax[$i-1])
                        return redirect()->back()->with('message', 'Tax name does not exist!');
                }
                else
                    $tax[$i-1]['rate'] = 0;

                $qty[] = $current_line[1];
                $price[] = $current_line[3];
                $discount[] = $current_line[4];
            }
            $i++;
        }
        //return $unit;
        $data = $request->except('document');
        $data['reference_no'] = 'sr-' . date("Ymd") . '-'. date("his");
        $data['user_id'] = Auth::user()->id;
        $document = $request->document;
        if ($document) {
            $v = Validator::make(
                [
                    'extension' => strtolower($request->document->getClientOriginalExtension()),
                ],
                [
                    'extension' => 'in:jpg,jpeg,png,gif,pdf,csv,docx,xlsx,txt',
                ]
            );
            if ($v->fails())
                return redirect()->back()->withErrors($v->errors());

            $ext = pathinfo($document->getClientOriginalName(), PATHINFO_EXTENSION);
            $documentName = $data['reference_no'] . '.' . $ext;
            $document->move('public/documents/sale', $documentName);
            $data['document'] = $documentName;
        }
        $item = 0;
        $grand_total = $data['shipping_cost'];
        Sale::create($data);
        $ot_crm_sale_data = Sale::latest()->first();
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
        
        foreach ($product_data as $key => $product) {
            if($product['tax_method'] == 1){
                $net_unit_price = $price[$key] - $discount[$key];
                $product_tax = $net_unit_price * ($tax[$key]['rate'] / 100) * $qty[$key];
                $total = ($net_unit_price * $qty[$key]) + $product_tax;
            }
            elseif($product['tax_method'] == 2){
                $net_unit_price = (100 / (100 + $tax[$key]['rate'])) * ($price[$key] - $discount[$key]);
                $product_tax = ($price[$key] - $discount[$key] - $net_unit_price) * $qty[$key];
                $total = ($price[$key] - $discount[$key]) * $qty[$key];
            }
            if($data['sale_status'] == 1 && $unit[$key]!='n/a'){
                $sale_unit_id = $unit[$key]['id'];
                if($unit[$key]['operator'] == '*')
                    $quantity = $qty[$key] * $unit[$key]['operation_value'];
                elseif($unit[$key]['operator'] == '/')
                    $quantity = $qty[$key] / $unit[$key]['operation_value'];
                $product['qty'] -= $quantity;
                $product_warehouse = SaleProductWarehouse::where([
                    ['product_id', $product['id']],
                    ['warehouse_id', $data['warehouse_id']]
                ])->first();
                $product_warehouse->qty -= $quantity;
                $product->save();
                $product_warehouse->save();
            }
            else
                $sale_unit_id = 0;
            //collecting mail data
            $mail_data['products'][$key] = $product['name'];
            if($product['type'] == 'digital')
                $mail_data['file'][$key] = url('/public/product/files').'/'.$product['file'];
            else
                $mail_data['file'][$key] = '';
            if($sale_unit_id)
                $mail_data['unit'][$key] = $unit[$key]['unit_code'];
            else
                $mail_data['unit'][$key] = '';

            $product_sale = new Product_Sale();
            $product_sale->sale_id = $ot_crm_sale_data->id;
            $product_sale->product_id = $product['id'];
            $product_sale->qty = $mail_data['qty'][$key] = $qty[$key];
            $product_sale->sale_unit_id = $sale_unit_id;
            $product_sale->net_unit_price = number_format((float)$net_unit_price, 2, '.', '');
            $product_sale->discount = $discount[$key] * $qty[$key];
            $product_sale->tax_rate = $tax[$key]['rate'];
            $product_sale->tax = number_format((float)$product_tax, 2, '.', '');
            $product_sale->total = $mail_data['total'][$key] = number_format((float)$total, 2, '.', '');
            $product_sale->save();
            $ot_crm_sale_data->total_qty += $qty[$key];
            $ot_crm_sale_data->total_discount += $discount[$key] * $qty[$key];
            $ot_crm_sale_data->total_tax += number_format((float)$product_tax, 2, '.', '');
            $ot_crm_sale_data->total_price += number_format((float)$total, 2, '.', '');
        }
        $ot_crm_sale_data->item = $key + 1;
        $ot_crm_sale_data->order_tax = ($ot_crm_sale_data->total_price - $ot_crm_sale_data->order_discount) * ($data['order_tax_rate'] / 100);
        $ot_crm_sale_data->grand_total = ($ot_crm_sale_data->total_price + $ot_crm_sale_data->order_tax + $ot_crm_sale_data->shipping_cost) - $ot_crm_sale_data->order_discount;
        $ot_crm_sale_data->save();
        $message = 'Sale imported successfully';
        if($ot_crm_customer_data->email){
            //collecting male data
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['reference_no'] = $ot_crm_sale_data->reference_no;
            $mail_data['sale_status'] = $ot_crm_sale_data->sale_status;
            $mail_data['payment_status'] = $ot_crm_sale_data->payment_status;
            $mail_data['total_qty'] = $ot_crm_sale_data->total_qty;
            $mail_data['total_price'] = $ot_crm_sale_data->total_price;
            $mail_data['order_tax'] = $ot_crm_sale_data->order_tax;
            $mail_data['order_tax_rate'] = $ot_crm_sale_data->order_tax_rate;
            $mail_data['order_discount'] = $ot_crm_sale_data->order_discount;
            $mail_data['shipping_cost'] = $ot_crm_sale_data->shipping_cost;
            $mail_data['grand_total'] = $ot_crm_sale_data->grand_total;
            $mail_data['paid_amount'] = $ot_crm_sale_data->paid_amount;
           
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleSale.index');
    }

    public function createSale($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('sales-edit')){
            $ot_crm_biller_list = SaleBiller::where('is_active', true)->get();
            $ot_crm_customer_list = SaleCustomer::where('is_active', true)->get();
            $ot_crm_customer_group_all = SaleCustomerGroup::where('is_active', true)->get();
            $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
            $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
            $ot_crm_sale_data = Sale::find($id);
            $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $id)->get();
            $ot_crm_product_list = SaleProduct::where([
                                    ['featured', 1],
                                    ['is_active', true]
                                ])->get();
            foreach ($ot_crm_product_list as $key => $product) {
                $images = explode(",", $product->image);
                $product->base_image = $images[0];
            }
            $product_number = count($ot_crm_product_list);
            $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
            $ot_crm_brand_list = SaleBrand::where('is_active',true)->get();
            $ot_crm_category_list = SaleCategory::where('is_active',true)->get();
            $ot_crm_coupon_list = SaleCoupon::where('is_active',true)->get();

            return view('backend.sale.create_sale',compact('ot_crm_biller_list', 'ot_crm_customer_list', 'ot_crm_warehouse_list', 'ot_crm_tax_list', 'ot_crm_sale_data','ot_crm_product_sale_data', 'ot_crm_pos_setting_data', 'ot_crm_brand_list', 'ot_crm_category_list', 'ot_crm_coupon_list', 'ot_crm_product_list', 'product_number', 'ot_crm_customer_group_all'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function edit($id)
    {
        $ot_crm_customer_list = SaleCustomer::where('is_active', true)->get();
        $ot_crm_warehouse_list = SaleWarehouse::where('is_active', true)->get();
        $ot_crm_biller_list = SaleBiller::where('is_active', true)->get();
        $ot_crm_tax_list = SaleTax::where('is_active', true)->get();
        $ot_crm_sale_data = Sale::find($id);
        $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $id)->get();
        $data['title'] = _trans('common.Sale Edit');
        return view('sale::sale.sale.edit',compact('data','ot_crm_customer_list', 'ot_crm_warehouse_list', 'ot_crm_biller_list', 'ot_crm_tax_list', 'ot_crm_sale_data','ot_crm_product_sale_data'));

    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/sale/documents', $documentName);
            $data['document'] = $documentName;
        }
        $balance = $data['grand_total'] - $data['paid_amount'];
        if($balance < 0 || $balance > 0)
            $data['payment_status'] = 2;
        else
            $data['payment_status'] = 4;
        $ot_crm_sale_data = Sale::find($id);
        $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $id)->get();
        $data['created_at'] = date("Y-m-d", strtotime(str_replace("/", "-", $data['created_at'])));
        $product_id = $data['product_id'];
        $imei_number = $data['imei_number'];
        $product_batch_id = $data['product_batch_id'];
        $product_code = $data['product_code'];
        $product_variant_id = $data['product_variant_id'];
        $qty = $data['qty'];
        $sale_unit = $data['sale_unit'];
        $net_unit_price = $data['net_unit_price'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $old_product_id = [];
        $product_sale = [];
        foreach ($ot_crm_product_sale_data as  $key => $product_sale_data) {
            $old_product_id[] = $product_sale_data->product_id;
            $old_product_variant_id[] = null;
            $ot_crm_product_data = SaleProduct::find($product_sale_data->product_id);

            if( ($ot_crm_sale_data->sale_status == 1) && ($ot_crm_product_data->type == 'combo') ) {
                $product_list = explode(",", $ot_crm_product_data->product_list);
                $variant_list = explode(",", $ot_crm_product_data->variant_list);
                if($ot_crm_product_data->variant_list)
                    $variant_list = explode(",", $ot_crm_product_data->variant_list);
                else
                    $variant_list = [];
                $qty_list = explode(",", $ot_crm_product_data->qty_list);

                foreach ($product_list as $index=>$child_id) {
                    $child_data = SaleProduct::find($child_id);
                    if(count($variant_list) && $variant_list[$index]) {
                        $child_product_variant_data = SaleProductVariant::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index]]
                        ])->first();

                        $child_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index]],
                            ['warehouse_id', $ot_crm_sale_data->warehouse_id ],
                        ])->first();

                        $child_product_variant_data->qty += $product_sale_data->qty * $qty_list[$index];
                        $child_product_variant_data->save();
                    }
                    else {
                        $child_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $child_id],
                            ['warehouse_id', $ot_crm_sale_data->warehouse_id ],
                        ])->first();
                    }

                    $child_data->qty += $product_sale_data->qty * $qty_list[$index];
                    $child_warehouse_data->qty += $product_sale_data->qty * $qty_list[$index];

                    $child_data->save();
                    $child_warehouse_data->save();
                }
            }
            elseif( ($ot_crm_sale_data->sale_status == 1) && ($product_sale_data->sale_unit_id != 0)) {
                $old_product_qty = $product_sale_data->qty;
                $ot_crm_sale_unit_data = SaleUnit::find($product_sale_data->sale_unit_id);
                if ($ot_crm_sale_unit_data->operator == '*')
                    $old_product_qty = $old_product_qty * $ot_crm_sale_unit_data->operation_value;
                else
                    $old_product_qty = $old_product_qty / $ot_crm_sale_unit_data->operation_value;
                if($product_sale_data->variant_id) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($product_sale_data->product_id, $product_sale_data->variant_id)->first();
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($product_sale_data->product_id, $product_sale_data->variant_id, $ot_crm_sale_data->warehouse_id)
                    ->first();
                    $old_product_variant_id[$key] = $ot_crm_product_variant_data->id;
                    $ot_crm_product_variant_data->qty += $old_product_qty;
                    $ot_crm_product_variant_data->save();
                }
                elseif($product_sale_data->product_batch_id) {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_id', $product_sale_data->product_id],
                        ['product_batch_id', $product_sale_data->product_batch_id],
                        ['warehouse_id', $ot_crm_sale_data->warehouse_id]
                    ])->first();

                    $product_batch_data = SaleProductBatch::find($product_sale_data->product_batch_id);
                    $product_batch_data->qty += $old_product_qty;
                    $product_batch_data->save();
                }
                else
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($product_sale_data->product_id, $ot_crm_sale_data->warehouse_id)
                    ->first();
                $ot_crm_product_data->qty += $old_product_qty;
                $ot_crm_product_warehouse_data->qty += $old_product_qty;
                $ot_crm_product_data->save();
                $ot_crm_product_warehouse_data->save();
            }

            if($product_sale_data->imei_number) {
                if($ot_crm_product_warehouse_data->imei_number)
                    $ot_crm_product_warehouse_data->imei_number .= ',' . $product_sale_data->imei_number;
                else
                    $ot_crm_product_warehouse_data->imei_number = $product_sale_data->imei_number;
                $ot_crm_product_warehouse_data->save();
            }

            if($product_sale_data->variant_id && !(in_array($old_product_variant_id[$key], $product_variant_id)) ){
                $product_sale_data->delete();
            }
            elseif( !(in_array($old_product_id[$key], $product_id)) )
                $product_sale_data->delete();
        }
        foreach ($product_id as $key => $pro_id) {
            $ot_crm_product_data = SaleProduct::find($pro_id);
            $product_sale['variant_id'] = null;
            if($ot_crm_product_data->type == 'combo' && $data['sale_status'] == 1) {
                $product_list = explode(",", $ot_crm_product_data->product_list);
                $variant_list = explode(",", $ot_crm_product_data->variant_list);
                if($ot_crm_product_data->variant_list)
                    $variant_list = explode(",", $ot_crm_product_data->variant_list);
                else
                    $variant_list = [];
                $qty_list = explode(",", $ot_crm_product_data->qty_list);

                foreach ($product_list as $index => $child_id) {
                    $child_data = SaleProduct::find($child_id);
                    if(count($variant_list) && $variant_list[$index]) {
                        $child_product_variant_data = SaleProductVariant::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index] ],
                        ])->first();

                        $child_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index] ],
                            ['warehouse_id', $data['warehouse_id'] ],
                        ])->first();

                        $child_product_variant_data->qty -= $qty[$key] * $qty_list[$index];
                        $child_product_variant_data->save();
                    }
                    else {
                        $child_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $child_id],
                            ['warehouse_id', $data['warehouse_id'] ],
                        ])->first();
                    }
                        

                    $child_data->qty -= $qty[$key] * $qty_list[$index];
                    $child_warehouse_data->qty -= $qty[$key] * $qty_list[$index];

                    $child_data->save();
                    $child_warehouse_data->save();
                }
            }
            if($sale_unit[$key] != 'n/a') {
                $ot_crm_sale_unit_data = SaleUnit::where('unit_name', $sale_unit[$key])->first();
                $sale_unit_id = $ot_crm_sale_unit_data->id;
                if($data['sale_status'] == 1) {
                    $new_product_qty = $qty[$key];
                    if ($ot_crm_sale_unit_data->operator == '*') {
                        $new_product_qty = $new_product_qty * $ot_crm_sale_unit_data->operation_value;
                    } else {
                        $new_product_qty = $new_product_qty / $ot_crm_sale_unit_data->operation_value;
                    }
                    if($ot_crm_product_data->is_variant) {
                        $ot_crm_product_variant_data = SaleProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($pro_id, $ot_crm_product_variant_data->variant_id, $data['warehouse_id'])
                        ->first();
                        
                        $product_sale['variant_id'] = $ot_crm_product_variant_data->variant_id;
                        $ot_crm_product_variant_data->qty -= $new_product_qty;
                        $ot_crm_product_variant_data->save();
                    }
                    elseif($product_batch_id[$key]) {
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $pro_id],
                            ['product_batch_id', $product_batch_id[$key] ],
                            ['warehouse_id', $data['warehouse_id'] ]
                        ])->first();

                        $product_batch_data = SaleProductBatch::find($product_batch_id[$key]);
                        $product_batch_data->qty -= $new_product_qty;
                        $product_batch_data->save();
                    }
                    else {
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($pro_id, $data['warehouse_id'])
                        ->first();
                    }
                    $ot_crm_product_data->qty -= $new_product_qty;
                    $ot_crm_product_warehouse_data->qty -= $new_product_qty;
                    $ot_crm_product_data->save();
                    $ot_crm_product_warehouse_data->save();
                }
            }
            else
                $sale_unit_id = 0;

            //deduct imei number if available
            if($imei_number[$key]) {
                $imei_numbers = explode(",", $imei_number[$key]);
                $all_imei_numbers = explode(",", $ot_crm_product_warehouse_data->imei_number);
                foreach ($imei_numbers as $number) {
                    if (($j = array_search($number, $all_imei_numbers)) !== false) {
                        unset($all_imei_numbers[$j]);
                    }
                }
                $ot_crm_product_warehouse_data->imei_number = implode(",", $all_imei_numbers);
                $ot_crm_product_warehouse_data->save();   
            }
            
            //collecting mail data
            if($product_sale['variant_id']) {
                $variant_data = SaleVariant::select('name')->find($product_sale['variant_id']);
                $mail_data['products'][$key] = $ot_crm_product_data->name . ' [' . $variant_data->name . ']';
            }
            else
                $mail_data['products'][$key] = $ot_crm_product_data->name;

            if($ot_crm_product_data->type == 'digital')
                $mail_data['file'][$key] = url('/public/product/files').'/'.$ot_crm_product_data->file;
            else
                $mail_data['file'][$key] = '';
            if($sale_unit_id)
                $mail_data['unit'][$key] = $ot_crm_sale_unit_data->unit_code;
            else
                $mail_data['unit'][$key] = '';

            $product_sale['sale_id'] = $id ;
            $product_sale['product_id'] = $pro_id;
            $product_sale['imei_number'] = $imei_number[$key];
            $product_sale['product_batch_id'] = $product_batch_id[$key];
            $product_sale['qty'] = $mail_data['qty'][$key] = $qty[$key];
            $product_sale['sale_unit_id'] = $sale_unit_id;
            $product_sale['net_unit_price'] = $net_unit_price[$key];
            $product_sale['discount'] = $discount[$key];
            $product_sale['tax_rate'] = $tax_rate[$key];
            $product_sale['tax'] = $tax[$key];
            $product_sale['total'] = $mail_data['total'][$key] = $total[$key];
            
            if($product_sale['variant_id'] && in_array($product_variant_id[$key], $old_product_variant_id)) {
                SaleProductSale::where([
                    ['product_id', $pro_id],
                    ['variant_id', $product_sale['variant_id']],
                    ['sale_id', $id]
                ])->update($product_sale);
            }
            elseif( $product_sale['variant_id'] === null && (in_array($pro_id, $old_product_id)) ) {
                SaleProductSale::where([
                    ['sale_id', $id],
                    ['product_id', $pro_id]
                ])->update($product_sale);
            }
            else
                SaleProductSale::create($product_sale);
        }
        $ot_crm_sale_data->update($data);
        $ot_crm_customer_data = SaleCustomer::find($data['customer_id']);
        $message = 'Sale updated successfully';
        //collecting mail data
        if($ot_crm_customer_data->email){
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['reference_no'] = $ot_crm_sale_data->reference_no;
            $mail_data['sale_status'] = $ot_crm_sale_data->sale_status;
            $mail_data['payment_status'] = $ot_crm_sale_data->payment_status;
            $mail_data['total_qty'] = $ot_crm_sale_data->total_qty;
            $mail_data['total_price'] = $ot_crm_sale_data->total_price;
            $mail_data['order_tax'] = $ot_crm_sale_data->order_tax;
            $mail_data['order_tax_rate'] = $ot_crm_sale_data->order_tax_rate;
            $mail_data['order_discount'] = $ot_crm_sale_data->order_discount;
            $mail_data['shipping_cost'] = $ot_crm_sale_data->shipping_cost;
            $mail_data['grand_total'] = $ot_crm_sale_data->grand_total;
            $mail_data['paid_amount'] = $ot_crm_sale_data->paid_amount;
           
        }

        Toastr::success( $message, 'Success');
        return redirect()->route('saleSale.index');
    }

    public function printLastReciept()
    {
        $sale = Sale::where('sale_status', 1)->latest()->first();
        return redirect()->route('sale.invoice', $sale->id);
    }

    public function genInvoice($id)
    {
        $ot_crm_sale_data = Sale::find($id);
        $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $id)->get();
        $ot_crm_biller_data = SaleBiller::find($ot_crm_sale_data->biller_id);
        $ot_crm_warehouse_data = SaleWarehouse::find($ot_crm_sale_data->warehouse_id);
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
        $ot_crm_payment_data = SalePayment::where('sale_id', $id)->get();
        $numberInWords = $ot_crm_sale_data->grand_total;

        return view('sale::sale.sale.invoice', compact('ot_crm_sale_data', 'ot_crm_product_sale_data', 'ot_crm_biller_data', 'ot_crm_warehouse_data', 'ot_crm_customer_data', 'ot_crm_payment_data', 'numberInWords'));
    }

    public function addPayment(Request $request)
    {
        $data = $request->all();
        if(!$data['amount'])
            $data['amount'] = 0.00;
        
        $ot_crm_sale_data = Sale::find($data['sale_id']);
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
        $ot_crm_sale_data->paid_amount += $data['amount'];
        $balance = $ot_crm_sale_data->grand_total - $ot_crm_sale_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $ot_crm_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $ot_crm_sale_data->payment_status = 4;
        
        if($data['paid_by_id'] == 1)
            $paying_method = 'Cash';
        elseif ($data['paid_by_id'] == 2)
            $paying_method = 'Gift Card';
        elseif ($data['paid_by_id'] == 3)
            $paying_method = 'Credit Card';
        elseif($data['paid_by_id'] == 4)
            $paying_method = 'Cheque';
        elseif($data['paid_by_id'] == 5)
            $paying_method = 'Paypal';
        elseif($data['paid_by_id'] == 6)
            $paying_method = 'Deposit';
        elseif($data['paid_by_id'] == 7)
            $paying_method = 'Points';


        $cash_register_data = SaleCashRegister::where([
            ['user_id', Auth::id()],
            ['warehouse_id', $ot_crm_sale_data->warehouse_id],
            ['status', true]
        ])->first();

        $ot_crm_payment_data = new SalePayment();
        $ot_crm_payment_data->user_id = Auth::id();
        $ot_crm_payment_data->sale_id = $ot_crm_sale_data->id;
        if($cash_register_data)
            $ot_crm_payment_data->cash_register_id = $cash_register_data->id;
        $ot_crm_payment_data->account_id = $data['account_id'];
        $data['payment_reference'] = 'spr-' . date("Ymd") . '-'. date("his");
        $ot_crm_payment_data->payment_reference = $data['payment_reference'];
        $ot_crm_payment_data->amount = $data['amount'];
        $ot_crm_payment_data->change = $data['paying_amount'] - $data['amount'];
        $ot_crm_payment_data->paying_method = $paying_method;
        $ot_crm_payment_data->payment_note = $data['payment_note'];
        $ot_crm_payment_data->save();
        $ot_crm_sale_data->save();

        $ot_crm_payment_data = SalePayment::latest()->first();
        $data['payment_id'] = $ot_crm_payment_data->id;

        if($paying_method == 'Gift Card'){
            $ot_crm_gift_card_data = SaleGiftCard::find($data['gift_card_id']);
            $ot_crm_gift_card_data->expense += $data['amount'];
            $ot_crm_gift_card_data->save();
            PaymentWithGiftCard::create($data);
        }
        elseif($paying_method == 'Credit Card'){
            $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
            Stripe::setApiKey($ot_crm_pos_setting_data->stripe_secret_key);
            $token = $data['stripeToken'];
            $amount = $data['amount'];

            $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $ot_crm_sale_data->customer_id)->first();

            if(!$ot_crm_payment_with_credit_card_data) {
                // Create a Customer:
                $customer = \Stripe\SaleCustomer::create([
                    'source' => $token
                ]);
                
                // Charge the Customer instead of the card:
                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'customer' => $customer->id,
                ]);
                $data['customer_stripe_id'] = $customer->id;
            }
            else {
                $customer_id = 
                $ot_crm_payment_with_credit_card_data->customer_stripe_id;

                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'customer' => $customer_id, // Previously stored, then retrieved
                ]);
                $data['customer_stripe_id'] = $customer_id;
            }
            $data['customer_id'] = $ot_crm_sale_data->customer_id;
            $data['charge_id'] = $charge->id;
            PaymentWithCreditCard::create($data);
        }
        elseif ($paying_method == 'Cheque') {
            SalePaymentWithCheque::create($data);
        }
        elseif ($paying_method == 'Paypal') {
            $provider = new ExpressCheckout;
            $paypal_data['items'] = [];
            $paypal_data['items'][] = [
                'name' => 'Paid Amount',
                'price' => $data['amount'],
                'qty' => 1
            ];
            $paypal_data['invoice_id'] = $ot_crm_payment_data->payment_reference;
            $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
            $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess/'.$ot_crm_payment_data->id);
            $paypal_data['cancel_url'] = url('/sale');

            $total = 0;
            foreach($paypal_data['items'] as $item) {
                $total += $item['price']*$item['qty'];
            }

            $paypal_data['total'] = $total;
            $response = $provider->setExpressCheckout($paypal_data);
            return redirect($response['paypal_link']);
        }
        elseif ($paying_method == 'Deposit') {
            $ot_crm_customer_data->expense += $data['amount'];
            $ot_crm_customer_data->save();
        }
        elseif ($paying_method == 'Points') {
            $ot_crm_reward_point_setting_data = SaleRewardPointSetting::latest()->first();
            $used_points = ceil($data['amount'] / $ot_crm_reward_point_setting_data->per_point_amount);
            
            $ot_crm_payment_data->used_points = $used_points;
            $ot_crm_payment_data->save();

            $ot_crm_customer_data->points -= $used_points;
            $ot_crm_customer_data->save();
        }
        $message = 'Payment created successfully';
        if($ot_crm_customer_data->email){
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['sale_reference'] = $ot_crm_sale_data->reference_no;
            $mail_data['payment_reference'] = $ot_crm_payment_data->payment_reference;
            $mail_data['payment_method'] = $ot_crm_payment_data->paying_method;
            $mail_data['grand_total'] = $ot_crm_sale_data->grand_total;
            $mail_data['paid_amount'] = $ot_crm_payment_data->amount;
            
            
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleSale.index');
    }


    public function getPayment($id)
    {
        $data = SalePayment::with('account')->where('sale_id', $id)->get();
        $payments = $data->map(function ($payment) {
            return [
                'date' => $payment->created_at->format('d-m-Y'),
                'payment_reference' => $payment->payment_reference,
                'paid_amount' => $payment->amount,
                'paying_method' => $payment->paying_method,
                'payment_id' => $payment->id,
                'payment_note' => $payment->payment_note,
                'cheque_no' => $payment->cheque_no,
                'change' => $payment->change,
                'paying_amount' =>  $payment->amount + $payment->change,
                'created_at' => $payment->created_at->format('d-m-Y h:i A'),
                'account_name' => $payment->account->name,
                'account_id' => $payment->account->id,
            ];
        });
        return $payments;
    }
 

    public function updatePayment(Request $request)
    {
        $data = $request->all();
        //return $data;
        $ot_crm_payment_data = SalePayment::find($data['payment_id']);
        $ot_crm_sale_data = Sale::find($ot_crm_payment_data->sale_id);
        $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
        //updating sale table
        $amount_dif = $ot_crm_payment_data->amount - $data['edit_amount'];
        $ot_crm_sale_data->paid_amount = $ot_crm_sale_data->paid_amount - $amount_dif;
        $balance = $ot_crm_sale_data->grand_total - $ot_crm_sale_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $ot_crm_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $ot_crm_sale_data->payment_status = 4;
        $ot_crm_sale_data->save();

        if($ot_crm_payment_data->paying_method == 'Deposit') {
            $ot_crm_customer_data->expense -= $ot_crm_payment_data->amount;
            $ot_crm_customer_data->save();
        }
        elseif($ot_crm_payment_data->paying_method == 'Points') {
            $ot_crm_customer_data->points += $ot_crm_payment_data->used_points;
            $ot_crm_customer_data->save();
            $ot_crm_payment_data->used_points = 0;
        }
        if($data['edit_paid_by_id'] == 1)
            $ot_crm_payment_data->paying_method = 'Cash';
        elseif ($data['edit_paid_by_id'] == 2){
            if($ot_crm_payment_data->paying_method == 'Gift Card'){
                $ot_crm_payment_gift_card_data = PaymentWithGiftCard::where('payment_id', $data['payment_id'])->first();

                $ot_crm_gift_card_data = SaleGiftCard::find($ot_crm_payment_gift_card_data->gift_card_id);
                $ot_crm_gift_card_data->expense -= $ot_crm_payment_data->amount;
                $ot_crm_gift_card_data->save();

                $ot_crm_gift_card_data = SaleGiftCard::find($data['gift_card_id']);
                $ot_crm_gift_card_data->expense += $data['edit_amount'];
                $ot_crm_gift_card_data->save();

                $ot_crm_payment_gift_card_data->gift_card_id = $data['gift_card_id'];
                $ot_crm_payment_gift_card_data->save(); 
            }
            else{
                $ot_crm_payment_data->paying_method = 'Gift Card';
                $ot_crm_gift_card_data = SaleGiftCard::find($data['gift_card_id']);
                $ot_crm_gift_card_data->expense += $data['edit_amount'];
                $ot_crm_gift_card_data->save();
                PaymentWithGiftCard::create($data);
            }
        }
        elseif ($data['edit_paid_by_id'] == 3){
            $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
            Stripe::setApiKey($ot_crm_pos_setting_data->stripe_secret_key);
            if($ot_crm_payment_data->paying_method == 'Credit Card'){
                $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $ot_crm_payment_data->id)->first();

                \Stripe\Refund::create(array(
                  "charge" => $ot_crm_payment_with_credit_card_data->charge_id,
                ));

                $customer_id = 
                $ot_crm_payment_with_credit_card_data->customer_stripe_id;

                $charge = \Stripe\Charge::create([
                    'amount' => $data['edit_amount'] * 100,
                    'currency' => 'usd',
                    'customer' => $customer_id
                ]);
                $ot_crm_payment_with_credit_card_data->charge_id = $charge->id;
                $ot_crm_payment_with_credit_card_data->save();
            }
            else{
                $token = $data['stripeToken'];
                $amount = $data['edit_amount'];
                $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $ot_crm_sale_data->customer_id)->first();

                if(!$ot_crm_payment_with_credit_card_data) {
                    $customer = \Stripe\SaleCustomer::create([
                        'source' => $token
                    ]);

                    $charge = \Stripe\Charge::create([
                        'amount' => $amount * 100,
                        'currency' => 'usd',
                        'customer' => $customer->id,
                    ]);
                    $data['customer_stripe_id'] = $customer->id;
                }
                else {
                    $customer_id = 
                    $ot_crm_payment_with_credit_card_data->customer_stripe_id;

                    $charge = \Stripe\Charge::create([
                        'amount' => $amount * 100,
                        'currency' => 'usd',
                        'customer' => $customer_id
                    ]);
                    $data['customer_stripe_id'] = $customer_id;
                }
                $data['customer_id'] = $ot_crm_sale_data->customer_id;
                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            }
            $ot_crm_payment_data->paying_method = 'Credit Card';
        }
        elseif($data['edit_paid_by_id'] == 4){
            if($ot_crm_payment_data->paying_method == 'Cheque'){
                $ot_crm_payment_cheque_data = SalePaymentWithCheque::where('payment_id', $data['payment_id'])->first();
                $ot_crm_payment_cheque_data->cheque_no = $data['edit_cheque_no'];
                $ot_crm_payment_cheque_data->save(); 
            }
            else{
                $ot_crm_payment_data->paying_method = 'Cheque';
                $data['cheque_no'] = $data['edit_cheque_no'];
                SalePaymentWithCheque::create($data);
            }
        }
        elseif($data['edit_paid_by_id'] == 5){
            //updating payment data
            $ot_crm_payment_data->amount = $data['edit_amount'];
            $ot_crm_payment_data->paying_method = 'Paypal';
            $ot_crm_payment_data->payment_note = $data['edit_payment_note'];
            $ot_crm_payment_data->save();

            $provider = new ExpressCheckout;
            $paypal_data['items'] = [];
            $paypal_data['items'][] = [
                'name' => 'Paid Amount',
                'price' => $data['edit_amount'],
                'qty' => 1
            ];
            $paypal_data['invoice_id'] = $ot_crm_payment_data->payment_reference;
            $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
            $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess/'.$ot_crm_payment_data->id);
            $paypal_data['cancel_url'] = url('/sale');

            $total = 0;
            foreach($paypal_data['items'] as $item) {
                $total += $item['price']*$item['qty'];
            }

            $paypal_data['total'] = $total;
            $response = $provider->setExpressCheckout($paypal_data);
            return redirect($response['paypal_link']);
        }   
        elseif($data['edit_paid_by_id'] == 6){
            $ot_crm_payment_data->paying_method = 'Deposit';
            $ot_crm_customer_data->expense += $data['edit_amount'];
            $ot_crm_customer_data->save();
        }
        elseif($data['edit_paid_by_id'] == 7) {
            $ot_crm_payment_data->paying_method = 'Points';
            $ot_crm_reward_point_setting_data = SaleRewardPointSetting::latest()->first();
            $used_points = ceil($data['edit_amount'] / $ot_crm_reward_point_setting_data->per_point_amount);
            $ot_crm_payment_data->used_points = $used_points;
            $ot_crm_customer_data->points -= $used_points;
            $ot_crm_customer_data->save();
        }
        //updating payment data
        $ot_crm_payment_data->account_id = $data['account_id'];
        $ot_crm_payment_data->amount = $data['edit_amount'];
        $ot_crm_payment_data->change = $data['edit_paying_amount'] - $data['edit_amount'];
        $ot_crm_payment_data->payment_note = $data['edit_payment_note'];
        $ot_crm_payment_data->save();
        $message = 'Payment updated successfully';
        //collecting male data
        if($ot_crm_customer_data->email){
            $mail_data['email'] = $ot_crm_customer_data->email;
            $mail_data['sale_reference'] = $ot_crm_sale_data->reference_no;
            $mail_data['payment_reference'] = $ot_crm_payment_data->payment_reference;
            $mail_data['payment_method'] = $ot_crm_payment_data->paying_method;
            $mail_data['grand_total'] = $ot_crm_sale_data->grand_total;
            $mail_data['paid_amount'] = $ot_crm_payment_data->amount;
            
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleSale.index');
    }

    public function deletePayment(Request $request)
    {
        $ot_crm_payment_data = SalePayment::find($request['id']);
        $ot_crm_sale_data = Sale::where('id', $ot_crm_payment_data->sale_id)->first();
        $ot_crm_sale_data->paid_amount -= $ot_crm_payment_data->amount;
        $balance = $ot_crm_sale_data->grand_total - $ot_crm_sale_data->paid_amount;
        if($balance > 0 || $balance < 0)
            $ot_crm_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $ot_crm_sale_data->payment_status = 4;
        $ot_crm_sale_data->save();

        if ($ot_crm_payment_data->paying_method == 'Gift Card') {
            $ot_crm_payment_gift_card_data = PaymentWithGiftCard::where('payment_id', $request['id'])->first();
            $ot_crm_gift_card_data = SaleGiftCard::find($ot_crm_payment_gift_card_data->gift_card_id);
            $ot_crm_gift_card_data->expense -= $ot_crm_payment_data->amount;
            $ot_crm_gift_card_data->save();
            $ot_crm_payment_gift_card_data->delete();
        }
        elseif($ot_crm_payment_data->paying_method == 'Credit Card'){
            $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $request['id'])->first();
            $ot_crm_pos_setting_data = SalePosSetting::latest()->first();
            Stripe::setApiKey($ot_crm_pos_setting_data->stripe_secret_key);
            \Stripe\Refund::create(array(
              "charge" => $ot_crm_payment_with_credit_card_data->charge_id,
            ));

            $ot_crm_payment_with_credit_card_data->delete();
        }
        elseif ($ot_crm_payment_data->paying_method == 'Cheque') {
            $ot_crm_payment_cheque_data = SalePaymentWithCheque::where('payment_id', $request['id'])->first();
            $ot_crm_payment_cheque_data->delete();
        }
        elseif ($ot_crm_payment_data->paying_method == 'Paypal') {
            $ot_crm_payment_paypal_data = PaymentWithPaypal::where('payment_id', $request['id'])->first();
            if($ot_crm_payment_paypal_data){
                $provider = new ExpressCheckout;
                $response = $provider->refundTransaction($ot_crm_payment_paypal_data->transaction_id);
                $ot_crm_payment_paypal_data->delete();
            }
        }
        elseif ($ot_crm_payment_data->paying_method == 'Deposit'){
            $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
            $ot_crm_customer_data->expense -= $ot_crm_payment_data->amount;
            $ot_crm_customer_data->save();
        }
        elseif ($ot_crm_payment_data->paying_method == 'Points'){
            $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
            $ot_crm_customer_data->points += $ot_crm_payment_data->used_points;
            $ot_crm_customer_data->save();
        }
        $ot_crm_payment_data->delete();
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleSale.index');
    }

    public function todaySale()
    {
        $data['total_sale_amount'] = Sale::whereDate('created_at', date("Y-m-d"))->sum('grand_total');
        $data['total_payment'] = SalePayment::whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['cash_payment'] = SalePayment::where([
                                    ['paying_method', 'Cash']
                                ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['credit_card_payment'] = SalePayment::where([
                                    ['paying_method', 'Credit Card']
                                ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['gift_card_payment'] = SalePayment::where([
                                    ['paying_method', 'Gift Card']
                                ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['deposit_payment'] = SalePayment::where([
                                    ['paying_method', 'Deposit']
                                ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['cheque_payment'] = SalePayment::where([
                                    ['paying_method', 'Cheque']
                                ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['paypal_payment'] = SalePayment::where([
                                    ['paying_method', 'Paypal']
                                ])->whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['total_sale_return'] = Returns::whereDate('created_at', date("Y-m-d"))->sum('grand_total');
        $data['total_expense'] = Expense::whereDate('created_at', date("Y-m-d"))->sum('amount');
        $data['total_cash'] = $data['total_payment'] - ($data['total_sale_return'] + $data['total_expense']);
        return $data;
    }

    public function todayProfit($warehouse_id)
    {
        if($warehouse_id == 0)
            $product_sale_data = SaleProductSale::select(DB::raw('product_id, product_batch_id, sum(qty) as sold_qty, sum(total) as sold_amount'))->whereDate('created_at', date("Y-m-d"))->groupBy('product_id', 'product_batch_id')->get();
        else
            $product_sale_data = Sale::join('product_sales', 'sales.id', '=', 'product_sales.sale_id')
            ->select(DB::raw('product_sales.product_id, product_sales.product_batch_id, sum(product_sales.qty) as sold_qty, sum(product_sales.total) as sold_amount'))
            ->where('sales.warehouse_id', $warehouse_id)->whereDate('sales.created_at', date("Y-m-d"))
            ->groupBy('product_sales.product_id', 'product_sales.product_batch_id')->get();

        $product_revenue = 0;
        $product_cost = 0;
        $profit = 0;
        foreach ($product_sale_data as $key => $product_sale) {
            if($warehouse_id == 0) {
                if($product_sale->product_batch_id)
                    $product_purchase_data = ProductPurchase::where([
                        ['product_id', $product_sale->product_id],
                        ['product_batch_id', $product_sale->product_batch_id]
                    ])->get();
                else
                    $product_purchase_data = ProductPurchase::where('product_id', $product_sale->product_id)->get();
            }
            else {
                if($product_sale->product_batch_id) {
                    $product_purchase_data = Purchase::join('product_purchases', 'purchases.id', '=', 'product_purchases.purchase_id')
                    ->where([
                        ['product_purchases.product_id', $product_sale->product_id],
                        ['product_purchases.product_batch_id', $product_sale->product_batch_id],
                        ['purchases.warehouse_id', $warehouse_id]
                    ])->select('product_purchases.*')->get();
                }
                else
                    $product_purchase_data = Purchase::join('product_purchases', 'purchases.id', '=', 'product_purchases.purchase_id')
                    ->where([
                        ['product_purchases.product_id', $product_sale->product_id],
                        ['purchases.warehouse_id', $warehouse_id]
                    ])->select('product_purchases.*')->get();
            }

            $purchased_qty = 0;
            $purchased_amount = 0;
            $sold_qty = $product_sale->sold_qty;
            $product_revenue += $product_sale->sold_amount;
            foreach ($product_purchase_data as $key => $product_purchase) {
                $purchased_qty += $product_purchase->qty;
                $purchased_amount += $product_purchase->total;
                if($purchased_qty >= $sold_qty) {
                    $qty_diff = $purchased_qty - $sold_qty;
                    $unit_cost = $product_purchase->total / $product_purchase->qty;
                    $purchased_amount -= ($qty_diff * $unit_cost);
                    break;
                }
            }

            $product_cost += $purchased_amount;
            $profit += $product_sale->sold_amount - $purchased_amount;
        }
        
        $data['product_revenue'] = $product_revenue;
        $data['product_cost'] = $product_cost;
        if($warehouse_id == 0)
            $data['expense_amount'] = Expense::whereDate('created_at', date("Y-m-d"))->sum('amount');
        else
            $data['expense_amount'] = Expense::where('warehouse_id', $warehouse_id)->whereDate('created_at', date("Y-m-d"))->sum('amount');

        $data['profit'] = $profit - $data['expense_amount'];
        return $data;
    }

    public function deleteBySelection(Request $request)
    {
        $sale_id = $request['saleIdArray'];
        foreach ($sale_id as $id) {
            $ot_crm_sale_data = Sale::find($id);
            $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $id)->get();
            $ot_crm_delivery_data = Delivery::where('sale_id',$id)->first();
            if($ot_crm_sale_data->sale_status == 3)
                $message = 'Draft deleted successfully';
            else
                $message = 'Sale deleted successfully';
            foreach ($ot_crm_product_sale_data as $product_sale) {
                $ot_crm_product_data = SaleProduct::find($product_sale->product_id);
                //adjust product quantity
                if( ($ot_crm_sale_data->sale_status == 1) && ($ot_crm_product_data->type == 'combo') ){
                    $product_list = explode(",", $ot_crm_product_data->product_list);
                    if($ot_crm_product_data->variant_list)
                        $variant_list = explode(",", $ot_crm_product_data->variant_list);
                    else
                        $variant_list = [];
                    $qty_list = explode(",", $ot_crm_product_data->qty_list);

                    foreach ($product_list as $index=>$child_id) {
                        $child_data = SaleProduct::find($child_id);
                        if(count($variant_list) && $variant_list[$index]) {
                            $child_product_variant_data = SaleProductVariant::where([
                                ['product_id', $child_id],
                                ['variant_id', $variant_list[$index] ]
                            ])->first();

                            $child_warehouse_data = SaleProductWarehouse::where([
                                ['product_id', $child_id],
                                ['variant_id', $variant_list[$index] ],
                                ['warehouse_id', $ot_crm_sale_data->warehouse_id ],
                            ])->first();

                             $child_product_variant_data->qty += $product_sale->qty * $qty_list[$index];
                             $child_product_variant_data->save();
                        }
                        else {
                            $child_warehouse_data = SaleProductWarehouse::where([
                                ['product_id', $child_id],
                                ['warehouse_id', $ot_crm_sale_data->warehouse_id ],
                            ])->first();
                        }

                        $child_data->qty += $product_sale->qty * $qty_list[$index];
                        $child_warehouse_data->qty += $product_sale->qty * $qty_list[$index];

                        $child_data->save();
                        $child_warehouse_data->save();
                    }
                }
                elseif(($ot_crm_sale_data->sale_status == 1) && ($product_sale->sale_unit_id != 0)){
                    $ot_crm_sale_unit_data = SaleUnit::find($product_sale->sale_unit_id);
                    if ($ot_crm_sale_unit_data->operator == '*')
                        $product_sale->qty = $product_sale->qty * $ot_crm_sale_unit_data->operation_value;
                    else
                        $product_sale->qty = $product_sale->qty / $ot_crm_sale_unit_data->operation_value;
                    if($product_sale->variant_id) {
                        $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($ot_crm_product_data->id, $product_sale->variant_id)->first();
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($ot_crm_product_data->id, $product_sale->variant_id, $ot_crm_sale_data->warehouse_id)->first();
                        $ot_crm_product_variant_data->qty += $product_sale->qty;
                        $ot_crm_product_variant_data->save();
                    }
                    elseif($product_sale->product_batch_id) {
                        $ot_crm_product_batch_data = SaleProductBatch::find($product_sale->product_batch_id);
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                            ['product_batch_id', $product_sale->product_batch_id],
                            ['warehouse_id', $ot_crm_sale_data->warehouse_id]
                        ])->first();

                        $ot_crm_product_batch_data->qty -= $product_sale->qty;
                        $ot_crm_product_batch_data->save();
                    }
                    else {
                        $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($ot_crm_product_data->id, $ot_crm_sale_data->warehouse_id)->first();
                    }

                    $ot_crm_product_data->qty += $product_sale->qty;
                    $ot_crm_product_warehouse_data->qty += $product_sale->qty;
                    $ot_crm_product_data->save();
                    $ot_crm_product_warehouse_data->save();
                }
                $product_sale->delete();
            }
            $ot_crm_payment_data = SalePayment::where('sale_id', $id)->get();
            foreach ($ot_crm_payment_data as $payment) {
                if($payment->paying_method == 'Gift Card'){
                    $ot_crm_payment_with_gift_card_data = PaymentWithGiftCard::where('payment_id', $payment->id)->first();
                    $ot_crm_gift_card_data = SaleGiftCard::find($ot_crm_payment_with_gift_card_data->gift_card_id);
                    $ot_crm_gift_card_data->expense -= $payment->amount;
                    $ot_crm_gift_card_data->save();
                    $ot_crm_payment_with_gift_card_data->delete();
                }
                elseif($payment->paying_method == 'Cheque'){
                    $ot_crm_payment_cheque_data = SalePaymentWithCheque::where('payment_id', $payment->id)->first();
                    $ot_crm_payment_cheque_data->delete();
                }
                elseif($payment->paying_method == 'Credit Card'){
                    $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment->id)->first();
                    $ot_crm_payment_with_credit_card_data->delete();
                }
                elseif($payment->paying_method == 'Paypal'){
                    $ot_crm_payment_paypal_data = PaymentWithPaypal::where('payment_id', $payment->id)->first();
                    if($ot_crm_payment_paypal_data)
                        $ot_crm_payment_paypal_data->delete();
                }
                elseif($payment->paying_method == 'Deposit'){
                    $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
                    $ot_crm_customer_data->expense -= $payment->amount;
                    $ot_crm_customer_data->save();
                }
                $payment->delete();
            }
            if($ot_crm_delivery_data)
                $ot_crm_delivery_data->delete();
            if($ot_crm_sale_data->coupon_id) {
                $ot_crm_coupon_data = SaleCoupon::find($ot_crm_sale_data->coupon_id);
                $ot_crm_coupon_data->used -= 1;
                $ot_crm_coupon_data->save();
            }
            $ot_crm_sale_data->delete();
        }
        return 'Sale deleted successfully!';
    }
    
    public function destroy($id)
    {
        $url = url()->previous();
        $ot_crm_sale_data = Sale::find($id);
        $ot_crm_product_sale_data = SaleProductSale::where('sale_id', $id)->get();
        $ot_crm_delivery_data = SaleDelivery::where('sale_id',$id)->first();
        if($ot_crm_sale_data->sale_status == 3)
            $message = 'Draft deleted successfully';
        else
            $message = 'Sale deleted successfully';

        foreach ($ot_crm_product_sale_data as $product_sale) {
            $ot_crm_product_data = SaleProduct::find($product_sale->product_id);
            //adjust product quantity
            if( ($ot_crm_sale_data->sale_status == 1) && ($ot_crm_product_data->type == 'combo') ) {
                $product_list = explode(",", $ot_crm_product_data->product_list);
                $variant_list = explode(",", $ot_crm_product_data->variant_list);
                $qty_list = explode(",", $ot_crm_product_data->qty_list);
                if($ot_crm_product_data->variant_list)
                    $variant_list = explode(",", $ot_crm_product_data->variant_list);
                else
                    $variant_list = [];
                foreach ($product_list as $index=>$child_id) {
                    $child_data = SaleProduct::find($child_id);
                    if(count($variant_list) && $variant_list[$index]) {
                        $child_product_variant_data = SaleProductVariant::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index] ]
                        ])->first();

                        $child_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $child_id],
                            ['variant_id', $variant_list[$index] ],
                            ['warehouse_id', $ot_crm_sale_data->warehouse_id ],
                        ])->first();

                         $child_product_variant_data->qty += $product_sale->qty * $qty_list[$index];
                         $child_product_variant_data->save();
                    }
                    else {
                        $child_warehouse_data = SaleProductWarehouse::where([
                            ['product_id', $child_id],
                            ['warehouse_id', $ot_crm_sale_data->warehouse_id ],
                        ])->first();
                    }

                    $child_data->qty += $product_sale->qty * $qty_list[$index];
                    $child_warehouse_data->qty += $product_sale->qty * $qty_list[$index];

                    $child_data->save();
                    $child_warehouse_data->save();
                }
            }
            elseif(($ot_crm_sale_data->sale_status == 1) && ($product_sale->sale_unit_id != 0)) {
                $ot_crm_sale_unit_data = SaleUnit::find($product_sale->sale_unit_id);
                if ($ot_crm_sale_unit_data->operator == '*')
                    $product_sale->qty = $product_sale->qty * $ot_crm_sale_unit_data->operation_value;
                else
                    $product_sale->qty = $product_sale->qty / $ot_crm_sale_unit_data->operation_value;
                if($product_sale->variant_id) {
                    $ot_crm_product_variant_data = SaleProductVariant::select('id', 'qty')->FindExactProduct($ot_crm_product_data->id, $product_sale->variant_id)->first();
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithVariant($ot_crm_product_data->id, $product_sale->variant_id, $ot_crm_sale_data->warehouse_id)->first();
                    $ot_crm_product_variant_data->qty += $product_sale->qty;
                    $ot_crm_product_variant_data->save();
                }
                elseif($product_sale->product_batch_id) {
                    $ot_crm_product_batch_data = SaleProductBatch::find($product_sale->product_batch_id);
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::where([
                        ['product_batch_id', $product_sale->product_batch_id],
                        ['warehouse_id', $ot_crm_sale_data->warehouse_id]
                    ])->first();

                    $ot_crm_product_batch_data->qty -= $product_sale->qty;
                    $ot_crm_product_batch_data->save();
                }
                else {
                    $ot_crm_product_warehouse_data = SaleProductWarehouse::FindProductWithoutVariant($ot_crm_product_data->id, $ot_crm_sale_data->warehouse_id)->first();
                }
                    
                $ot_crm_product_data->qty += $product_sale->qty;
                $ot_crm_product_warehouse_data->qty += $product_sale->qty;
                $ot_crm_product_data->save();
                $ot_crm_product_warehouse_data->save();
            }
            if($product_sale->imei_number) {
                if($ot_crm_product_warehouse_data->imei_number)
                    $ot_crm_product_warehouse_data->imei_number .= ',' . $product_sale->imei_number;
                else
                    $ot_crm_product_warehouse_data->imei_number = $product_sale->imei_number;
                $ot_crm_product_warehouse_data->save();
            }
            $product_sale->delete();
        }

        $ot_crm_payment_data = SalePayment::where('sale_id', $id)->get();
        foreach ($ot_crm_payment_data as $payment) {
            if($payment->paying_method == 'Gift Card'){
                $ot_crm_payment_with_gift_card_data = PaymentWithGiftCard::where('payment_id', $payment->id)->first();
                $ot_crm_gift_card_data = SaleGiftCard::find($ot_crm_payment_with_gift_card_data->gift_card_id);
                $ot_crm_gift_card_data->expense -= $payment->amount;
                $ot_crm_gift_card_data->save();
                $ot_crm_payment_with_gift_card_data->delete();
            }
            elseif($payment->paying_method == 'Cheque'){
                $ot_crm_payment_cheque_data = SalePaymentWithCheque::where('payment_id', $payment->id)->first();
                $ot_crm_payment_cheque_data->delete();
            }
            elseif($payment->paying_method == 'Credit Card'){
                $ot_crm_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment->id)->first();
                $ot_crm_payment_with_credit_card_data->delete();
            }
            elseif($payment->paying_method == 'Paypal'){
                $ot_crm_payment_paypal_data = PaymentWithPaypal::where('payment_id', $payment->id)->first();
                if($ot_crm_payment_paypal_data)
                    $ot_crm_payment_paypal_data->delete();
            }
            elseif($payment->paying_method == 'Deposit'){
                $ot_crm_customer_data = SaleCustomer::find($ot_crm_sale_data->customer_id);
                $ot_crm_customer_data->expense -= $payment->amount;
                $ot_crm_customer_data->save();
            }
            $payment->delete();
        }
        if($ot_crm_delivery_data)
            $ot_crm_delivery_data->delete();
        if($ot_crm_sale_data->coupon_id) {
            $ot_crm_coupon_data = SaleCoupon::find($ot_crm_sale_data->coupon_id);
            $ot_crm_coupon_data->used -= 1;
            $ot_crm_coupon_data->save();
        }
        $ot_crm_sale_data->delete();

        Toastr::success(_trans('response.Sale deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
