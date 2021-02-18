<?php

namespace App\Http\Resources\Downtime;

use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
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
                'id' => $item->id,
                'name' => $item->eqipment->name,
                'html_class' => $item->eqipment->html_class,
                'level' => $item->eqipment->level + 2,
                'children_count' => $item->eqipment->children_count,
                'downtime' => $item->downtime,
            ];
        }

        return $result;
    }

}
