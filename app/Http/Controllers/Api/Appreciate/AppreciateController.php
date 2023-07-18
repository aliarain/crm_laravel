<?php

namespace App\Http\Controllers\Api\Appreciate;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Repositories\Hrm\Appreciate\AppreciateRepository;

class AppreciateController extends Controller
{
    use ApiReturnFormatTrait;
    
    protected $appreciate;
    public function __construct(AppreciateRepository $appreciate)
    {
        $this->appreciate = $appreciate;
    }

    public function store(Request $request)
    {
       return  $response =  $this->appreciate->createAppreciate($request);
       
    }
    public function index()
    {
        return \App\Models\Hrm\Appreciate::first()->appreciateFrom;
    }
}
