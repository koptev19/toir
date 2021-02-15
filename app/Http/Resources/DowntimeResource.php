<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

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
            ];
        }

        return $result;
    }

}
