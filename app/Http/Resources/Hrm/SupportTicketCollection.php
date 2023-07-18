<?php

namespace App\Http\Resources\Hrm;

use App\Helpers\CoreApp\Traits\DateHandler;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SupportTicketCollection extends ResourceCollection
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
                    'file' => uploaded_asset($data->attachment_file_id),
                    'type_name' => @$data->type->name,
                    'type_color' => appColorCodePrefix() . @$data->type->color_code,
                    'priority_name' => @$data->priority->name,
                    'priority_color' => appColorCodePrefix() . @$data->priority->color_code,
                    'date' => $this->dateFormatInPlainText($data->created_at),
                ];
            })
        ];
    }
}
