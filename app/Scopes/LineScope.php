<?php

namespace App\Scopes;

use App\Models\Equipment;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class LineScope implements Scope
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $line)
    {
        $builder->whereType(Equipment::TYPE_LINE)
            ->whereLevel(2);
    }
}