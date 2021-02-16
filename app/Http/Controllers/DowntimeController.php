<?php

namespace App\Http\Controllers;

use App\Helpers\TimeHelper;
use App\Http\Resources\DowntimeOperationsResource;
use App\Http\Resources\DowntimeResource;
use App\Models\Equipment;
use App\Models\History;
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
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function items(Request $request)
    {
        $parent = $request->parent ?? null;
        $dateFrom = $request->date_from ?? null;
        $dateTo = $request->date_to ?? Carbon::today()->format('Y-m-d');

        if(!$dateFrom) {
            abort(404);
        }

        $equipments = Equipment::whereParentId($parent)
            ->withCount('children')
            ->get();

        $downtimes = [];
        foreach($equipments as $equipment) {
            $downtimes[] = (object)[
                'eqipment' => $equipment,
                'downtime' => $this->getDowntime($dateFrom, $dateTo, $equipment),
            ];
        }

        return response()->json([
            'items' => new DowntimeResource($downtimes)
        ]);
    }

    public function operations(Request $request, Equipment $equipment)
    {
        $dateFrom = $request->date_from ?? null;
        $dateTo = $request->date_to ?? Carbon::today()->format('Y-m-d');
        if(!$dateFrom) {
            abort(404);
        }

        $histories = $this->getHistories($dateFrom, $dateTo, $equipment);

        return response()->json([
            'items' => new DowntimeOperationsResource($histories)
        ]);
    }

    private function getDowntime(string $dateFrom, string $dateTo, Equipment $equipment): string
    {
        $histories = $this->getHistories($dateFrom, $dateTo, $equipment);
        
        return TimeHelper::sumTime($histories->pluck('work_time')->toArray());
    }

    public function getHistories(string $dateFrom, string $dateTo, Equipment $equipment): Collection
    {
        return History::whereIn('equipment_id', $equipment->allChildrenAndSelfId())
            ->where('planned_date', '>=', $dateFrom)
            ->where('planned_date', '<=', $dateTo)
            ->get();
    }

}
