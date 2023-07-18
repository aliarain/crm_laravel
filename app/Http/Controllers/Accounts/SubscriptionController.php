<?php

namespace App\Http\Controllers\Accounts;

use Stripe;
use Session;
use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Accounts\SubscriptionPlan;
use Illuminate\Support\Facades\Request;

class SubscriptionController extends Controller
{

    public function index()
    {
        $data = [];
        $data['plans'] =  SubscriptionPlan::where('status_id', 1)->get();
        $data['title'] = _trans('common.Subscription');
        return view('backend.accounts.subscriptions.create', compact('data'));
    }

    public function subscribe(Request $request)
    {
       
        $user = auth()->user();
        $token =  @$request->stripeToken;
        $paymentMethod = @$request->paymentMethod;

        try {

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            if (is_null(@$user->stripe_id)) {
                // $stripeCustomer = $user->createAsStripeCustomer();
                $user->subscription('prod_HeC7XMT2SVe21K')->create($request->stripeToken);

            }

            \Stripe\Customer::createSource(
                $user->stripe_id,
                ['source' => $token]
            );

            $user->newSubscription('test', $input['plane'])
                ->create($paymentMethod, [
                    'email' => $user->email,
                ]);

            return back()->with('success', 'Subscription is completed.');
        } catch (Exception $e) {
            return back()->with('success', $e->getMessage());
        }
    }
}
