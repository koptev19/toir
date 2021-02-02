<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Http\Requests\EquipmentFormRequest;
use App\Models\Line;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
        $equipmentsTree = $this->getEquipmentsTree();
        
        return view('equipments.index', compact('equipmentsTree'));
    }

    /**
     * @param Request $request
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $parentId = $request->parent ?? null;
        $parent = Equipment::find($parentId);

        $equipmentsTree = $this->getEquipmentsTree($parent);

        return view('equipments.create', compact('equipmentsTree', 'parent'));
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
                Line::create($request->validated());
            } else {
                Equipment::create($request->validated());
            }
        } else {
            Workshop::create($request->validated());
        }

        return redirect()->route('equipments.index');
    }

    /**
     * @param Request $request
     * @param Equipment $equipment
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Equipment $equipment)
    {
        $equipmentsTree = $this->getEquipmentsTree($equipment);

        return view('equipments.edit', compact('equipment', 'equipmentsTree'));
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

        return redirect()->route('equipments.index');
    }

    
    /**
     * @param Request $request
     * @param Equipment $equipment
     * 
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, Equipment $equipment)
    {
        $equipmentsTree = $this->getEquipmentsTree($equipment);

        return view('equipments.show', compact('equipment', 'equipmentsTree'));
    }

    /**
     * @param Equipment|null $equipment = null
     * 
     * @return Collection
     */
    private function getEquipmentsTree(?Equipment $equipment = null, int $parentId = null): Collection
    {
        $parents = ($parentId ? Equipment::whereNull('parent_id') : Equipment::whereParentId($parentId))
            ->get();
        /*
        if($equipment) {
            $parentsId = $equipment->allParentsId();
            foreach($parents as $parent) {
                if(in_array($parent->id, $parentsId)) {
                    $parent->childrenTree = $this->getEquipmentsTree($equipment, $parent->id);
                }
            }
        }
        */
        return $parents;
    }

}
