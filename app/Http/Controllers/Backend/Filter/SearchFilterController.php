<?php

namespace App\Http\Controllers\Backend\Filter;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserFilterCollection;


class SearchFilterController extends Controller
{
    public function searchFilter(Request $request)
    {
        $keyword = $request->search;
        $users = User::where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%")
            ->orWhere('phone', 'LIKE', "%{$keyword}%")
            ->select('id','name','email','phone')
            ->take(8)
            ->get();

            return new UserFilterCollection($users);
    }
}
