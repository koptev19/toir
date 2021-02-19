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
                'id' => $item->date . '.' . $item->type . '.' . $item->eqipment->id,
                'parent_id' => $item->date . '.' . $item->type . ($item->eqipment->parent_id ? '.' . $item->eqipment->parent_id : ''),
                'name' => $item->eqipment->name,
                'html_class' => $item->eqipment->html_class,
                'level' => $item->eqipment->level + 2,
                'children_count' => $item->eqipment->children_count,
                'downtime' => implode(' : ', explode(':', $item->downtime)),
                'worktime' => implode(' : ', explode(':', $item->worktime)),
            ];
        }

        return $result;
    }

}
