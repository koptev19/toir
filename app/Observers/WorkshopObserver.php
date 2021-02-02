<?php

namespace App\Observers;

use App\Models\Workshop;

class WorkshopObserver
{

    /**
     * @param  \App\Models\Workshop $workshop
     * 
     * @return void
     */
    public function created(Workshop $workshop)
    {
        $workshop->update(['workshop_id' => $workshop->id]);
    }

}