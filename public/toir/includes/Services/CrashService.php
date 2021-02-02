<?php

class CrashService
{
    /**
     * @param array $create
     * @param string $source
     * 
     * @return int
     */
    public static function create(array $create): ?int
    {
        $stopId = StopService::createIfNotExists($create['LINE_ID'], strtotime($create['DATE']), false, false);

        $create['STATUS'] = Crash::STATUS_NEW;
        $create['STOP_ID'] = $stopId;
        $crashId = Crash::create($create);

        $stop = Stop::find($stopId);
        $stop->CRASH_ID = $crashId;
        $stop->save();

        $crash = Crash::find($crashId);
        $crash->STOP_ID = $stopId;
        $crash->save();

        return $crashId;
    }

}