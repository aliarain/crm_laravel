<?php

namespace App\Repositories\Hrm\Appreciate;

use Validator;
use App\Models\Hrm\Appreciate;
use App\Helpers\CoreApp\Traits\ApiReturnFormatTrait;
use App\Models\coreApp\Relationship\RelationshipTrait;



class AppreciateRepository
{

    use RelationshipTrait,ApiReturnFormatTrait;

    protected $appreciate;
    public function __construct(Appreciate $appreciate)
    {
        $this->appreciate = $appreciate;
    }


    public function createAppreciate($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'appreciate_to' => 'required',
                'message' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->responseWithError(_trans('validation.Validation field required'), $validator->errors(), 422);
        }

        $appreciate = new Appreciate;
        $appreciate->user_id=$request->appreciate_to;
        $appreciate->appreciate_by=auth()->id();
        $appreciate->message=$request->message;
        $result= $appreciate->save();

        return $this->responseWithSuccess(_trans('validation.Appreciate sent successfully'), [], 200);
    }
}
