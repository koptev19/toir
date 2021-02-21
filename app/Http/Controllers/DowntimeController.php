<?php

namespace App\Http\Controllers;

use App\Contracts\DowntimeContract;
use App\Helpers\TimeHelper;
use App\Http\Resources\Downtime;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $parent = explode('.', $request->id);

        $histories = app(DowntimeContract::class)->getOperations($parent[0], $parent[1] ?? null, $parent[2] ?? null);

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

        $downtimes = app(DowntimeContract::class)->getDowntimesGroupedDate($dateFrom, $dateTo);

        return response()->json([
            'items' => new Downtime\DateResource($downtimes),
            'total' => TimeHelper::sumTime($downtimes->pluck('downtime'))
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

        $downtimes = app(DowntimeContract::class)->getDowntimesGroupedType($date);

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
        $parentEquipmentId = !empty($parentArray[2]) ? (int)$parentArray[2] : null;

        $downtimes = app(DowntimeContract::class)->getDowntimesGroupedEquipment($date, $type, $parentEquipmentId);
        
        return response()->json([
            'items' => new Downtime\EquipmentResource($downtimes)
        ]);
    }

}
