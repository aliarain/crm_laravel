<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\SubscriptionPlan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $data = [];
        $data['title'] = _trans('payment.Payments');
        $data['intent'] = [
            'intent' => auth()->user()->createSetupIntent()
        ];
        return view('backend.accounts.subscriptions.payment', compact('data'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            $plan = SubscriptionPlan::where('identifier', $request->plan)
                ->orWhere('identifier', 'basic')
                ->first();

            $request->user()->newSubscription('default', $plan->stripe_id)->create($request->token);

            return back();
        } catch (\Throwable $th) {
        }
    }
}
