<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DowntimeResource extends JsonResource
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
                'id' => $item->eqipment->id,
                'equipment_name' => $item->eqipment->name,
                'html_class' => $item->eqipment->html_class,
                'level' => $item->eqipment->level,
                'children_count' => $item->eqipment->children_count,
                'downtime' => $item->downtime,
                'exists_operations' => $item->downtime != '00:00',
            ];
        }

        return $result;
    }

}
