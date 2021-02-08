<?php

namespace App\Http\Controllers;

use App\Models\Accept;
use App\Http\Requests\AcceptFormRequest;
use Illuminate\Http\Request;

/**
 * Class AcceptController
 * @package App\Http\Controllers
 */
class AcceptController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $accepts = Accept::all();
        return view('accepts.index', compact('accepts'));
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('accepts.create');
    }

    /**
     * @param DepartmentFormRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AcceptFormRequest $request)
    {
        Accept::create($request->validated());

        return redirect()->route('accepts.index');
    }

    /**
     * @param Request $request
     * @param Accept $accept
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Accept $accept)
    {
        return view('accepts.edit', compact('accept'));
    }

    /**
     * @param DepartmentFormRequest $request
     * @param Accept $accept
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AcceptFormRequest $request, Accept $accept)
    {
        $accept->update($request->validated());

        return redirect()->route('accepts.index');
    }

    /**
     * @param DepartmentFormRequest $request
     * @param Accept $accept
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Accept $accept)
    {
        $accept->delete();

        return redirect()->route('accepts.index');
    }


}
