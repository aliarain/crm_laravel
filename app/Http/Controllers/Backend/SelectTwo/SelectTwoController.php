<?php

namespace App\Http\Controllers\Backend\SelectTwo;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SelectTwoController extends Controller
{
    public function getUsers(Request $request)
    {
        return User::role('Client')->where('name', 'LIKE', "%$request->term%")->select('id', 'name')->take(10)->get();
    }
}
