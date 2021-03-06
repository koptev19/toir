<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Http\Requests\EquipmentFormRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Line;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\Request;

/**
 * Class EquipmentController
 * @package App\Http\Controllers
 */
class EquipmentController extends Controller
{
    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('equipments.index');
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $parentId = $request->parent ?? null;
        $parent = $parentId ? Equipment::find($parentId) : null;

        $parentsId = $parent ? array_merge($parent->allParentsId(), [$parent->id]) : [];

        return view('equipments.create', compact('parentsId', 'parent'));
    }

    /**
     * @param EquipmentFormRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EquipmentFormRequest $request)
    {
        if($request->parent_id) {
            $parent = Equipment::find($request->parent_id);
            if($parent->type === Equipment::TYPE_WORKSHOP) {
                $equipment = Line::create($request->validated());
            } else {
                $equipment = Equipment::create($request->validated());
            }
        } else {
            $equipment = Workshop::create($request->validated());
        }

        return redirect()->route('equipments.show', $equipment);
    }

    /**
     * @param Request $request
     * @param Equipment $equipment
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Equipment $equipment)
    {
        $parentsId = array_merge($equipment->allParentsId(), [$equipment->id]);
        $users = User::whereConnected(true)->get();

        return view('equipments.edit', compact('equipment', 'parentsId', 'users'));
    }

    /**
     * @param EquipmentFormRequest $request
     * @param Equipment $equipment
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EquipmentFormRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());

        if($request->documents_added) {
            $equipment->documents()->attach($request->documents_added);
        }

        if($request->documents_deleted) {
            $equipment->documents()->detach($request->documents_deleted);
        }

        return redirect()->route('equipments.show', $equipment);
    }
    
    /**
     * @param Request $request
     * @param Equipment $equipment
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Equipment $equipment)
    {
        $parentsId = array_merge($equipment->allParentsId(), [$equipment->id]);
        $plans = $equipment->plans()->whereIn('service_id', \Auth::user()->available_departments_id)->get();
        $works = $equipment->works()->whereIn('service_id', \Auth::user()->available_departments_id)->get();
        return view('equipments.show', compact('equipment', 'parentsId', 'plans', 'works'));
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function children(Request $request)
    {
        $equipments = Equipment::whereParentId($request->parent ?: null)
            ->withCount('children')
            ->get();

        return response()->json([
            'items' => new EquipmentResource($equipments)
        ]);
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function parents(Request $request, Equipment $equipment)
    {
        $equipments = [$equipment];
        $e = $equipment;
        while($e->parent) {
            $equipments[] = $e->parent;
            $e = $e->parent;
        }

        return response()->json([
            'items' => new EquipmentResource(collect(array_reverse($equipments)))
        ]);
    }
}
