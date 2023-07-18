<?php

namespace App\Http\Controllers\Backend\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    public function index(Request $request)
    {
        $data['title'] = _trans('common.Superadmin Dashboard');
        return view('backend.__superadmin_dashboard', compact('data'));
    }
}
