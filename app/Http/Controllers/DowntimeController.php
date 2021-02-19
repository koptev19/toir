<?php

namespace App\Http\Controllers;

use App\Helpers\TimeHelper;
use App\Http\Resources\Downtime;
use App\Models\Equipment;
use App\Models\History;
use App\Models\Operation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class DowntimeController
 * @package App\Http\Controllers
 */
class DowntimeController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::today()->startOfMonth()->format('Y-m-d');
        $dateTo = $request->date_to ?? Carbon::today()->format('Y-m-d');

        session()->flashInput([
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
        ]);

        return view('downtimes.index');
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function items(Request $request)
    {
        $parent = $request->parent ?? null;
        $level = $request->level ?? 1;
        $dateFrom = $request->date_from ?? null;
        $dateTo = $request->date_to ?? Carbon::today()->format('Y-m-d');

        if($level == 1) {
            return $this->itemsDate($dateFrom, $dateTo);
        } elseif($level == 2) {
            return $this->itemsType($parent);
        } else {
            return $this->itemsEquipment($parent);
        }
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function operations(Request $request)
    {
        $parentArray = explode('.', $request->id);

        $equipment = isset($parentArray[2]) ? Equipment::find($parentArray[2]) : null;

        $histories = $this->getHistories($parentArray[0], $parentArray[1] ?? null, $equipment);

        return response()->json([
            'items' => new Downtime\OperationsResource($histories)
        ]);
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    private function itemsDate(string $dateFrom, string $dateTo)
    {
        if(!$dateFrom) {
            abort(404);
        }

        $downtimes = [];

        $groupedHistories = History::whereResult('Y')
            ->where('planned_date', '>=', $dateFrom)
            ->where('planned_date', '<=', $dateTo)
            ->orderBy('planned_date', 'asc')
            ->get()
            ->groupBy('date_database');

        foreach($groupedHistories as $date => $histories) {
            $worktimes = $histories->where('downtime_type', '!=', History::DOWNTIME_TYPE_REPAIR)->pluck('work_time');

            $downtimes[] = (object)[
                'date_format' => $date,
                'date' => (new Carbon($date))->format('d.m.Y'),
                'downtime' => TimeHelper::sumWorkTime($worktimes),
                'worktime' => '00:00',
            ];
        }

        return response()->json([
            'items' => new Downtime\DateResource($downtimes),
            'total' => TimeHelper::sumTime(collect($downtimes)->pluck('downtime'))
        ]);
    }

    /**
     * @param string $date
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    private function itemsType(string $date)
    {
        if(!$date) {
            abort(404);
        }

        $groupedHistories = History::with(['equipment', 'equipment.workshop'])
            ->whereResult('Y')
            ->wherePlannedDate($date)
            ->get()
            ->groupBy('downtime_type');

        foreach($groupedHistories as $type => $histories) {
            $downtimes[] = (object)[
                'date' => $date,
                'type' => $type,
                'downtime' => $type !== History::DOWNTIME_TYPE_REPAIR ? TimeHelper::sumWorkTime($histories->pluck('work_time')->toArray()) : '',
                'worktime' => '00:00',
            ];
        }

        return response()->json([
            'items' => new Downtime\TypeResource($downtimes)
        ]);
    }

    /**
     * @param string $parent
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    private function itemsEquipment(string $parent)
    {
        if(!$parent) {
            abort(404);
        }

        $parentArray = explode('.', $parent);
        $date = $parentArray[0];
        $type = $parentArray[1];
        $parentEquipmentId = $parentArray[2] ?? null;

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
                    'downtime' => $type !== History::DOWNTIME_TYPE_REPAIR ? TimeHelper::sumWorkTime($histories->pluck('work_time')->toArray()) : '',
                    'worktime' => '00:00',
                ];
            }
        }

        return response()->json([
            'items' => new Downtime\EquipmentResource($downtimes)
        ]);
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
