<?php

namespace App\Http\Controllers;

use App\Models\Accept;
use App\Models\AcceptHistory;
use App\Http\Requests\AcceptHistoryFormRequest;
use Illuminate\Http\Request;

/**
 * Class AcceptController
 * @package App\Http\Controllers
 */
class AcceptHistoryController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $acceptHistories = AcceptHistory::all();
        return view('accepts.index', compact('acceptHistories'));
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $accept = Accept::findOrFail($request->accept ?? null);

        return view('accept-histories.create', compact('accept'));
    }

    /**
     * @param AcceptHistoryFormRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AcceptHistoryFormRequest $request)
    {
        $params = $request->validated() + [
            'stage' => $request->comment ? AcceptHistory::STAGE_NEW : AcceptHistory::STAGE_DONE
        ];

        $acceptHistory = AcceptHistory::create($params);

        if($request->files_added) {
            $acceptHistory->files()->attach($request->files_added);
        }

        return redirect()->route('accept-histories.store-ok');
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeOk(Request $request)
    {
        return view('accept-histories.store-ok');
    }

}
