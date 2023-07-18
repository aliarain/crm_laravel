<?php

namespace Modules\Sale\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Modules\Sale\Entities\SaleCustomer;
use Modules\Sale\Entities\SaleGiftCard;
use Illuminate\Contracts\Support\Renderable;
use Modules\Sale\Entities\SaleGiftCardRecharge;

class GiftcardController extends Controller
{
    public function index()
    {
        $entries = isset($_GET['entries']) !== false ? $_GET['entries'] : 10;
        $search = isset($_GET['search']) !== false ? $_GET['search'] : '';
        $data['entries'] = $entries;
        $data['search'] = $search;

        $data['title'] = _trans('common.Giftcard List');
        $data['giftcards'] = SaleGiftCard::with('user','createdBy','customer')->where('is_active',true); 
        if ($search != "") {
            $data['giftcards'] = $data['giftcards']->where('card_no', 'like', '%' . $search . '%');
        }

        $data['giftcards'] = $data['giftcards']->where('is_active', true)->latest()->paginate($entries);
        $data['customers'] = SaleCustomer::where('is_active', true)->get();
        $data['users'] = User::all();

        return view('sale::sale.giftcard.index', compact('data'));

    }
    public function generateCode()
    {
        $code = random_int(100000, 999999);
        return $code;
    }

    public function store(Request $request)
    {
        

        $data = $request->all();

        if($request->input('user'))
            $data['customer_id'] = null;
        else
            $data['user_id'] = null;

        $data['is_active'] = true;
        $data['created_by'] = Auth::id();
        $data['expense'] = 0;
        SaleGiftCard::create($data);
        $message = 'GiftCard created successfully';
        if($data['user_id']){
            $ot_crm_user_data = User::find($data['user_id']);
            $data['email'] = $ot_crm_user_data->email;
            $data['name'] = $ot_crm_user_data->name;
            
            $message = 'GiftCard created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
        }
        else{
            $ot_crm_customer_data = SaleCustomer::find($data['customer_id']);
            if($ot_crm_customer_data->email){
                $data['email'] = $ot_crm_customer_data->email;
                $data['name'] = $ot_crm_customer_data->name;
               
                $message = 'GiftCard created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleGiftcard.index');

    }

    public function edit($id)
    {
        $ot_crm_gift_card_data = SaleGiftCard::find($id);
        return $ot_crm_gift_card_data;
    }

    public function update(Request $request)
    {
        $request['card_no'] = $request['card_no_edit'];

        $data = $request->all();
        $ot_crm_gift_card_data = SaleGiftCard::find($data['gift_card_id']);
        $ot_crm_gift_card_data->card_no = $data['card_no_edit'];
        $ot_crm_gift_card_data->amount = $data['amount_edit'];
        if($request->input('user_edit')){
            $ot_crm_gift_card_data->user_id = $data['user_id_edit'];
            $ot_crm_gift_card_data->customer_id = null;
        }
        else{
            $ot_crm_gift_card_data->user_id = null;
            $ot_crm_gift_card_data->customer_id = $data['customer_id_edit'];
        }
        $ot_crm_gift_card_data->expired_date = $data['expired_date_edit'];
        $ot_crm_gift_card_data->save();
        Toastr::success(_trans('message.Operation Successful'), 'Success');
        return redirect()->route('saleGiftcard.index');

    }

    public function recharge(Request $request, $id)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $ot_crm_gift_card_data = SaleGiftCard::find($data['gift_card_id']);
        if($ot_crm_gift_card_data->customer_id)
            $ot_crm_customer_data = SaleCustomer::find($ot_crm_gift_card_data->customer_id);
        else
            $ot_crm_customer_data = User::find($ot_crm_gift_card_data->user_id);
        
        $ot_crm_gift_card_data->amount += $data['amount'];
        $ot_crm_gift_card_data->save();
        SaleGiftCardRecharge::create($data);
        $message = 'GiftCard recharged successfully';
        if($ot_crm_customer_data->email){
            $data['email'] = $ot_crm_customer_data->email;
            $data['name'] = $ot_crm_customer_data->name;
            $data['card_no'] = $ot_crm_gift_card_data->card_no;
            $data['balance'] = $ot_crm_gift_card_data->amount - $ot_crm_gift_card_data->expense;
          
        }
        Toastr::success( $message, 'Success');
        return redirect()->route('saleGiftcard.index');
    }

    public function deleteBySelection(Request $request)
    {
        $gift_card_id = $request['gift_cardIdArray'];
        foreach ($gift_card_id as $id) {
            $ot_crm_gift_card_data = SaleGiftCard::find($id);
            $ot_crm_gift_card_data->is_active = false;
            $ot_crm_gift_card_data->save();
        }
        return 'Gift Card deleted successfully!';
    }

    public function destroy($id)
    {
        $ot_crm_gift_card_data = SaleGiftCard::find($id);
        $ot_crm_gift_card_data->is_active = false;
        $ot_crm_gift_card_data->save();
        Toastr::success(_trans('response.Gift Card deleted successfully'), 'Success');
        return response()->json(['status' => 'success']);
    }
}
