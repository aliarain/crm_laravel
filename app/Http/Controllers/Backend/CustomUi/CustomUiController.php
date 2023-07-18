<?php

namespace App\Http\Controllers\Backend\CustomUi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomUiController extends Controller
{
    public function index()
    {
        return view('backend.custom_ui.index');

    }
}
