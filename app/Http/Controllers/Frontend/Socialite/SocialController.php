<?php

namespace App\Http\Controllers\Frontend\Socialite;

use App\Http\Controllers\Controller;
use App\Models\coreApp\Social\SocialIdentity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request,$provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/');
        }

        $authUser = $this->findOrCreateUser($request,$user, $provider);
        Auth::login($authUser, true);
        return redirect('/')->with('success','Login Successfully');
    }


    public function findOrCreateUser(Request $request,$providerUser, $provider)
    {
        $account = SocialIdentity::whereProviderName($provider)
            ->whereProviderId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {
            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $request['email'] = $providerUser->getEmail();
                $request['name'] = $providerUser->getName() . $provider;
                $request['phone'] = rand(10, 100);

                $user = $this->driver->create($request);
                $user->identities()->create([
                    'provider_id' => $providerUser->getId(),
                    'provider_name' => $provider,
                ]);
            }
            return $user;
        }
    }
}
