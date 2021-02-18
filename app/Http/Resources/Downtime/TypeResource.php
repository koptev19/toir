<?php

namespace App\Http\Resources\Downtime;

use Illuminate\Http\Resources\Json\JsonResource;

class TypeResource extends JsonResource
{
    public const TYPE_CRASH = 'crash';
    public const TYPE_REPAIR = 'repair';
    public const TYPE_WORKS = 'works';
    public const TYPE_UNDEFINED = 'undefined';
    
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        $result = [];

        $names = [
            self::TYPE_CRASH => 'Авария',
            self::TYPE_REPAIR => 'Ремонт',
            self::TYPE_WORKS => 'ППР',
            self::TYPE_UNDEFINED => 'Не определено',
        ];

        foreach($this->resource as $item) {
            $result[] = [
                'id' => $item->id,
                'name' => $names[$item->type],
                'html_class' => 'link-dark',
                'level' => 2,
                'children_count' => $item->type != self::TYPE_UNDEFINED,
                'downtime' => $item->downtime,
            ];
        }

        return $result;
    }

}
