<?php

namespace App\Http\Controllers;

use App\Http\Resources\DowntimeResource;
use App\Models\Equipment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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

        $eqipments = Equipment::whereParentId($parent)->get();

        $downtimes = [];
        foreach($eqipments as $eqipment) {
            $downtimes[] = (object)[
                'eqipment' => $eqipment
            ];
        }

        return response()->json([
            'items' => new DowntimeResource($downtimes)
        ]);
    }

}
