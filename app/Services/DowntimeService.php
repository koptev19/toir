<?php

namespace App\Services;

use App\Contracts\DowntimeContract;
use App\Helpers\TimeHelper;
use App\Models\Equipment;
use App\Models\History;
use App\Models\Operation;
use App\Models\Worktime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class DowntimeService implements DowntimeContract
{
    /**
     * @param string $dateFrom
     * @param string $dateTo
     * 
     * @return Collection
     */
    public function getDowntimesGroupedDate(string $dateFrom, string $dateTo): Collection
    {
        $downtimes = [];

        $groupedHistories = History::whereResult('Y')
            ->where('planned_date', '>=', $dateFrom)
            ->where('planned_date', '<=', $dateTo)
            ->orderBy('work_time', 'asc')
            ->get()
            ->groupBy('date_database');

        foreach($groupedHistories as $date => $histories) {
            $worktimes = $histories->where('downtime_type', '!=', History::DOWNTIME_TYPE_REPAIR)->pluck('work_time');

            $worktime1 = $this->getWorktime($histories->where('downtime_type', '=', History::DOWNTIME_TYPE_WORKS), History::DOWNTIME_TYPE_WORKS);
            $worktime2 = $this->getWorktime($histories->where('downtime_type', '=', History::DOWNTIME_TYPE_CRASH), History::DOWNTIME_TYPE_CRASH);

            $downtimes[] = (object)[
                'date_format' => $date,
                'date' => (new Carbon($date))->format('d.m.Y'),
                'downtime' => TimeHelper::sumOverlayTimes($worktimes),
                'worktime' => TimeHelper::sumTime([$worktime1, $worktime2]),
            ];
        }

        return collect($downtimes);
    }

    /**
     * @param string $date
     * 
     * @return Collection
     */
    public function getDowntimesGroupedType(string $date): Collection
    {
        $downtimes = [];

        $groupedHistories = History::with(['equipment', 'equipment.workshop'])
            ->whereResult('Y')
            ->wherePlannedDate($date)
            ->get()
            ->groupBy('downtime_type');

        foreach($groupedHistories as $type => $histories) {
            $downtimes[] = (object)[
                'date' => $date,
                'type' => $type,
                'downtime' => $type !== History::DOWNTIME_TYPE_REPAIR ? TimeHelper::sumOverlayTimes($histories->pluck('work_time')) : '',
                'worktime' => $this->getWorktime($histories, $type),
            ];
        }

        return collect($downtimes);
    }

    /**
     * @param string $date
     * @param string $type
     * @param int|null $parentEquipmentId
     * 
     * @return Collection
     */
    public function getDowntimesGroupedEquipment(string $date, string $type, ?int $parentEquipmentId): Collection
    {
        $equipments = Equipment::whereParentId($parentEquipmentId)
            ->withCount('children')
            ->get();

        $downtimes = [];
        foreach($equipments as $equipment) {
            $histories = $this->getHistories($date, $type, $equipment);

            if($histories->count()) {
                $downtimes[] = (object)[
                    'date' => $date,
                    'type' => $type,
                    'eqipment' => $equipment,
                    'downtime' => $type !== History::DOWNTIME_TYPE_REPAIR ? TimeHelper::sumOverlayTimes($histories->pluck('work_time')) : '',
                    'worktime' => $this->getWorktime($histories, $type),
                ];
            }
        }

        return collect($downtimes);
    }

    /**
     * @param string $date
     * @param string|null $type
     * @param int|null $equipmentId
     * 
     * @return Collection
     */
    public function getOperations(string $date, ?string $type, ?int $equipmentId): Collection
    {
        $equipment = $equipmentId ? Equipment::find($equipmentId) : null;

        return $this->getHistories($date, $type, $equipment);
    }

    /**
     * @param Collection $histories
     * @param string $type
     * 
     * @return string
     */
    private function getWorktime(Collection $histories, string $type): string
    {
        $worktime = '00:00';
        if($type === History::DOWNTIME_TYPE_WORKS) {
            $operations = Operation::whereIn('id', $histories->pluck('operation_id'))->get();
            $worktimes = Worktime::whereIn('operation_id', $operations->pluck('id'))->whereAction('report')->get();
            $worktime = TimeHelper::sumWorkTime($worktimes->pluck('work_time'));
        }

        if($type === History::DOWNTIME_TYPE_CRASH) {
            $worktimeMinutes = $histories->map(function (History $history) {
                return count(explode(',', $history->owner)) * TimeHelper::getDiffMinutes($history->work_time);
            })->sum();

            $worktime = TimeHelper::getTimeByMinutes($worktimeMinutes);
        }

        return $worktime;
    }

    /**
     * @param string $date
     * @param string|null $type
     * @param Equipment|null $equipment
     * 
     * @return Collection
     */
    private function getHistories(string $date, ?string $type, ?Equipment $equipment): Collection
    {
        return History::whereResult('Y')
            ->wherePlannedDate($date)
            ->when($type == History::DOWNTIME_TYPE_CRASH, function (Builder $builder) {
                return $builder->whereReason(Operation::REASON_CRASH);
            })
            ->when($type == History::DOWNTIME_TYPE_REPAIR, function (Builder $builder) {
                return $builder->whereHas('equipment', function (Builder $builder) {
                    return $builder->whereHas('workshop', function (Builder $builder) {
                        return $builder->whereIsRepair(true);
                    });
                });
            })
            ->when($type == History::DOWNTIME_TYPE_WORKS, function (Builder $builder) {
                return $builder->whereSource(History::SOURCE_REPORT_DATE);
            })
            ->when($equipment, function (Builder $builder, Equipment $equipment) {
                return $builder->whereIn('equipment_id', $equipment->allChildrenAndSelfId());                
            })
            ->get();
    }
}