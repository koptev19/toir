<?php

namespace App\Http\Resources\Downtime;

use Illuminate\Http\Resources\Json\JsonResource;

class DateResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        $result = [];

        foreach($this->resource as $item) {
            $result[] = [
                'id' => $item->date_format,
                'parent_id' => null,
                'name' => $item->date,
                'html_class' => 'link-dark',
                'level' => 1,
                'children_count' => true,
                'downtime' => implode(' : ', explode(':', $item->downtime)),
                'worktime' => implode(' : ', explode(':', $item->worktime)),
            ];
        }

        return $result;
    }

}
