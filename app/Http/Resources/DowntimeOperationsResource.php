<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DowntimeOperationsResource extends JsonResource
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

        foreach($this->resource as $history) {
            $result[] = [
                'id' => $history->id,
                'equipment_name' => $history->equipment->line_path,
                'name' => $history->name,
                'date' => $history->date,
                'time' => $history->work_time,
                'owner' => $history->owner,
            ];
        }

        return $result;
    }

}
