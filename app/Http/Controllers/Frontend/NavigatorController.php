<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Content\AllContent;
use App\Http\Controllers\Controller;
use App\Helpers\CoreApp\Traits\PermissionTrait;

class NavigatorController extends Controller
{
    use PermissionTrait;
 
    public function index()
    {
        try {
            return view('frontend.crm_landing');
        } catch (\Exception $e) {
            abort('404', 'Not Found!');
        }
    }
    public function support()
    {
        $data['title']              = _trans('common.Support');
        $data['show']               = AllContent::where('slug', 'support-24-7')->first();
        return view('frontend.privacy_policy', compact('data'));
    }
    public function privacyPolicy()
    {
        $data['title']              = _trans('common.Privacy Policy');
        $data['show']               = AllContent::where('slug', 'privacy-policy')->first();
        return view('frontend.privacy_policy', compact('data'));
    }
    public function termsAndConditions()
    {
        $data['title']              = _trans('common.Terms And Conditions');
        $data['show']               = AllContent::where('slug', 'terms-of-use')->first();
        return view('frontend.terms_and_conditions', compact('data'));
    }
}
