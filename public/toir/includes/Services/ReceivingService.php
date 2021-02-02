<?php

class ReceivingService
{

    /**
     * @return int
     */
    public static function countNotDone(): int
    {
        return AcceptItem::filter([
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds(),
            'STAGE' => AcceptItem::STAGE_NEW,
        ])->count();
    }
}