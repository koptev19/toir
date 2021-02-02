<?php

class ToirSelectEquipmentController extends ToirController
{

    /**
     * @return void
     */
    public function index()
    {
        $parentId = (int)$_REQUEST['parent'] ?? 0;
        $equipments = $parentId ? Equipment::filter(['PARENT_ID' => $parentId])->get() : Workshop::all();
        $items = [];
        foreach($equipments as $equipment) {
            $items[] = [
                'ID' => $equipment->ID,
                'NAME' => $equipment->NAME,
            ];
        }
        echo json_encode(['items' => $items]);
//        $this->view('select_equipment/index', compact('equipments'));
    }

}