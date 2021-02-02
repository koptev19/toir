<?php

namespace App\Observers;

use App\Models\Equipment;
use App\Models\Line;

class LineObserver
{
    /**
     * @param  \App\Models\Line $line
     * 
     * @return void
     */
    public function creating(Line $line)
    {
        $line->workshop_id = $line->parent->workshop_id;
    }

    /**
     * @param  \App\Models\Line $line
     * 
     * @return void
     */
    public function created(Line $line)
    {
        $line->update(['line_id' => $line->id]);
    }

}