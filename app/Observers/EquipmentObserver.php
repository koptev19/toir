<?php

namespace App\Observers;

use App\Models\Equipment;

class EquipmentObserver
{
    /**
     * @param  \App\Models\Equipment $equipment
     * 
     * @return void
     */
    public function creating(Equipment $equipment)
    {
        $equipment->level = $equipment->parent->level + 1;
        $equipment->workshop_id = $equipment->parent->workshop_id;
        $equipment->line_id = $equipment->parent->line_id;
    }

}