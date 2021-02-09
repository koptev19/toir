<?php

namespace App\Observers;

use App\Models\AcceptHistory;
use App\Models\Equipment;

class AcceptHistoryObserver
{
    /**
     * @param  \App\Models\Equipment $equipment
     * 
     * @return void
     */
    public function creating(AcceptHistory $acceptHistory)
    {
        if(\Auth::check()) {
            $acceptHistory->author_id = \Auth::user()->id;
        }
        if(!$acceptHistory->stage) {
            $acceptHistory->stage = AcceptHistory::STAGE_NEW;
        }
    }

}