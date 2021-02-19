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
            $downtimes[] = (object)[
                'date_format' => $date,
                'date' => (new Carbon($date))->format('d.m.Y'),
                'downtime' => TimeHelper::sumTime($histories->pluck('work_time')->toArray()),
            ];
        }

        return response()->json([
            'items' => new Downtime\DateResource($downtimes)
        ]);
    }

    private function itemsType(string $date)
    {
        if(!$date) {
            abort(404);
        }

        $groupedHistories = History::with(['equipment', 'equipment.workshop'])
            ->whereResult('Y')
            ->wherePlannedDate($date)
            ->get()
            ->groupBy(function ($item) {
                if ($item->equipment->workshop->is_repair) {
                    return Downtime\TypeResource::TYPE_REPAIR;
                } elseif ($item->reason === Operation::REASON_CRASH) {
                    return Downtime\TypeResource::TYPE_CRASH;
                } elseif ($item->source === History::SOURCE_REPORT_DATE) {
                    return Downtime\TypeResource::TYPE_WORKS;
                } else {
                    return Downtime\TypeResource::TYPE_UNDEFINED;
                }
            });

        foreach($groupedHistories as $type => $histories) {
            $downtimes[] = (object)[
                'id' => $date . '.' . $type,
                'type' => $type,
                'downtime' => TimeHelper::sumTime($histories->pluck('work_time')->toArray()),
            ];
        }

        return response()->json([
            'items' => new Downtime\TypeResource($downtimes)
        ]);
    }

    private function itemsEquipment($parent)
    {
        if(!$parent) {
            abort(404);
        }

        $parentArray = explode('.', $parent);
        $date = $parentArray[0];
        $reason = $parentArray[1];
        $parentEquipmentId = $parentArray[2] ?? null;

        $equipments = Equipment::whereParentId($parentEquipmentId)
            ->withCount('children')
            ->get();

        $downtimes = [];
        foreach($equipments as $equipment) {
            $histories = $this->getHistories($date, $reason, $equipment);

            if($histories->count()) {
                $downtimes[] = (object)[
                    'id' => $date . '.' . $reason . '.' . $equipment->id,
                    'eqipment' => $equipment,
                    'downtime' => TimeHelper::sumTime($histories->pluck('work_time')->toArray()),
                ];
            }
        }

        return response()->json([
            'items' => new Downtime\EquipmentResource($downtimes)
        ]);
    }

    public function getHistories(string $date, ?string $type, ?Equipment $equipment): Collection
    {
        return History::whereResult('Y')
            ->wherePlannedDate($date)
            ->when($type == Downtime\TypeResource::TYPE_CRASH, function (Builder $builder) {
                return $builder->whereReason(Operation::REASON_CRASH);
            })
            ->when($type == Downtime\TypeResource::TYPE_REPAIR, function (Builder $builder) {
                return $builder->whereHas('equipment', function (Builder $builder) {
                    return $builder->whereHas('workshop', function (Builder $builder) {
                        return $builder->whereIsRepair(true);
                    });
                });
            })
            ->when($type == Downtime\TypeResource::TYPE_WORKS, function (Builder $builder) {
                return $builder->whereSource(History::SOURCE_REPORT_DATE);
            })
            ->when($equipment, function (Builder $builder, Equipment $equipment) {
                return $builder->whereIn('equipment_id', $equipment->allChildrenAndSelfId());                
            })
            ->get();
}

}
