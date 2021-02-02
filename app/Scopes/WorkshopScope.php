<?php

namespace App\Scopes;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class WorkshopScope implements Scope
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $workshop)
    {
        $builder->whereType(Equipment::TYPE_WORKSHOP)
            ->whereLevel(1);
    }
}