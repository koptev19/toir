<?php

namespace App\Http\Resources\Downtime;

use App\Models\History;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return array
     */
    public function toArray($request)
    {
        $result = [];

        $names = [
            History::DOWNTIME_TYPE_CRASH => 'Авария',
            History::DOWNTIME_TYPE_REPAIR => 'Ремонт',
            History::DOWNTIME_TYPE_WORKS => 'ППР',
            History::DOWNTIME_TYPE_UNDEFINED => 'Не определено',
        ];

        foreach($this->resource as $item) {
            $result[] = [
                'id' => $item->date . '.' . $item->type,
                'parent_id' => $item->date,
                'name' => $names[$item->type],
                'html_class' => 'link-dark',
                'level' => 2,
                'children_count' => $item->type != History::DOWNTIME_TYPE_UNDEFINED,
                'downtime' => implode(' : ', explode(':', $item->downtime)),
                'worktime' => implode(' : ', explode(':', $item->worktime)),
            ];
        }

        return $result;
    }

}
