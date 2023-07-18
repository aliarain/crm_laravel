<?php

namespace App\Http\Resources\Hrm;

use Carbon\Carbon;
use App\Helpers\CoreApp\Traits\DateHandler;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NoticeCollection extends ResourceCollection
{
    use DateHandler;
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($data) {
                return [
                    'id' => $data->id,
                    'subject' => $data->subject,
                    'date' => $this->dateFormatInPlainText($data->created_at),
                    'file' => uploaded_asset($data->attachment_file_id),

                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }


    public function singleList($data){
        return [
            'id' => $data->id,
            'subject' => $data->subject,
            'date' => $this->dateFormatInPlainText($data->created_at),
            'file' => uploaded_asset($data->attachment_file_id),

        ];
    }

}
