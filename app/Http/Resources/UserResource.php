<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        if(is_a($this->resource, Model::class)) {
            return $this->toArrayByModel($this->resource);
        } else {
            return $this->map(function(Model $model) {
                return $this->toArrayByModel($model);
            });
        }
    }

    private function toArrayByModel(Model $model)
    {
        return [
            'id' => $model->id,
            'fullname' => $model->fullname,
            'is_admin' =>  $model->is_admin,
            'connected' => $model->connected,
            'all_workshops' => $model->all_workshops,
            'workshops' => $model->workshops->pluck('id')->toArray(),
            'departments' => $model->departments->pluck('id')->toArray(),
        ];
    }

}
