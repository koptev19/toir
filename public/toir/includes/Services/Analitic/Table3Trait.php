<?php

trait Table3Trait
{
    /**
     * @param Service $service
     * @return string
     */
    public function table3CrashEquipments(Service $service): string
    {
        $filter = [
            '>=DATE' => $this->dateFrom,
            '<=DATE' => $this->dateTo,
        ];
        $crashes = Crash::filter($filter)->get();

        $htmlArray = [];
        foreach($crashes as $crash) {
            $count = $crash->serviceRequests()
                ->setFilter(['SERVICE_ID' => $service->ID])
                ->count();
            if($count > 0) {
                $htmlArray[] = '<div class="mb-3" style="height:100px;">' . $crash->equipment->getFullPath(' / ', true, true) . '</div>';
            }
        }

        return implode("", $htmlArray);
    }

    /**
     * @param Service $service
     * @return string
     */
    public function table3CrashDescriptions(Service $service): string
    {
        $filter = [
            '>=DATE' => $this->dateFrom,
            '<=DATE' => $this->dateTo,
        ];
        $crashes = Crash::filter($filter)->get();

        $htmlArray = [];
        foreach($crashes as $crash) {
            $count = $crash->serviceRequests()
                ->setFilter(['SERVICE_ID' => $service->ID])
                ->count();
            if($count > 0) {
                $htmlArray[] = '<div class="mb-3" style="height:100px;">' . $crash->_DESCRIPTION['TEXT'] . '</div>';
            }
        }

        return implode("", $htmlArray);
    }

    /**
     * @param Service $service
     * @return object
     */
    public function table3HistoryEquipmentsNames(Service $service): object
    {
        $filter = [
            '>=PLANNED_DATE' => $this->dateFrom,
            '<=PLANNED_DATE' => $this->dateTo,
            'RESULT' => 'Y',
            '!REASON' => Operation::REASON_CRASH,
        ];
        $histories = $service->histories()
            ->setFilter($filter)
            ->get();

        $result = (object)[
            'equipments' => '',
            'names' => '',
        ];
        foreach($histories as $history) {
            $result->equipments .= '<div class="mb-3" style="height:100px;">' . $history->equipment->getFullPath(' / ', true, true) . '</div>';
            $result->names .= '<div class="mb-3" style="height:100px;">' . $history->NAME . '</div>';
        }

        return $result;
    }

}