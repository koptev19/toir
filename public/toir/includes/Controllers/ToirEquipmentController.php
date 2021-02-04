<?php

class ToirEquipmentController extends ToirController
{

    /**
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {        
		if((int)$_REQUEST['id']){
            $element = Equipment::filter([
                'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
                'ID' => (int)$_REQUEST['id'],
            ])->first();

			$parents = $element->parents();
        }
        
		$this->view('equipment/index', [
			'selectedItem'=> $element->ID,
			'parents' => $parents ?? []
        ]);
    }

    public function show()
    {
        $node = Equipment::filter([
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
            'ID' => (int)$_REQUEST['ID'],
        ])->first();

		$children = $node->allChildren();
		$equipmentId[] = $node->ID;
		
		foreach ($children as $child){
			$equipmentId[] =  $child->ID;
		}
				
        $planOperations = $node ? $node->plans()->setFilter(['SERVICE_ID' => UserToir::current()->availableServicesIds])->get() : [];

        $works = $node ? $node->works()->setFilter(['SERVICE_ID' => UserToir::current()->availableServicesIds])->get() : [];

		$this->view('equipment/show', compact('node', 'planOperations', 'works'));
    }

    public function getNodes()
    {
        $parentId = $_REQUEST['PARENT_ID'] ? $_REQUEST['PARENT_ID'] : null;
        $nodes = Equipment::filter([
                'PARENT_ID' => $parentId,
                'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
            ])
            ->get();
        
        $items = [];
        foreach ($nodes as $node) {
            $items[$node->ID] = [
                'name' => $node->NAME,
                'countChildren' => Equipment::filter(['PARENT_ID' => $node->ID])->count(),
				'isLine' => $node->TYPE_ENUM == Equipment::TYPE_LINE,
            ];
        }

		echo json_encode($items);
    }

    public function create()
    {
        $parentId = (int)$_REQUEST['PARENT_ID'];
        $parent = Equipment::filter([
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
            'ID' => $parentId,
        ])->first();

        $this->view('equipment/create', compact('parent'));
    }

    public function store()
    {
        $data = $this->getDataByRequest();

        $parent = $data['PARENT_ID'] ? Equipment::find($data['PARENT_ID']) : null;

        if(!UserToir::current()->IS_ADMIN){
            if(!$parent || !in_array($parent->WORKSHOP_ID, UserToir::current()->availableWorkshopsIds)){
                header("Location: /main");
            }
        }

        $createFields = [
            'NAME' => $data['NAME'],
            'PARENT_ID' => $data['PARENT_ID'] ? $data['PARENT_ID'] : null,
            'TYPE' => $data['TYPE'],
            'LEVEL' => $parent ? $parent->LEVEL + 1 : 1,
            'WORKSHOP_ID' => $parent ? $parent->WORKSHOP_ID : null,
            'LINE_ID' => $parent ? $parent->LINE_ID : null,
        ];
        if ($parent) {
            if($parent->LEVEL == Workshop::LEVEL) {
                $id = Line::create($createFields);
            } else {
                $id = Equipment::create($createFields);                
            }
        } else {
            $createFields['MECHANIC_ID'] = $data['MECHANIC'];
            $id = Workshop::create($createFields);
        }

        $result = ['parentId' => $data['PARENT_ID']];
        if ($id) {
            $result['id'] = $id;
        }

        echo json_encode($result);
    }

    public function edit()
    {
        $node = Equipment::filter([
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
            'ID' => (int)$_REQUEST['ID'],
        ])->first();

        $this->view('equipment/edit', compact('node'));
    }

    public function update()
    {
        $node = Equipment::filter([
            'WORKSHOP_ID' => UserToir::current()->availableWorkshopsIds,
            'ID' => (int)$_REQUEST['ID'],
        ])->first();

        if($node) {
            $node->NAME = $_REQUEST['NAME'];
            $fields = ['SHORT_NAME', 'NACHALNIK_TSEKHA', 'MECHANIC_ID', 'ID_PAPKI_DOKUMENTATSIYA', 'OPISANIE', 'ZAVODSKOY_NOMER', 'INVENTARNYY_NOMER', 'DATA_VVODA', 'SOSTOYANIE', 'GARANTIYA_DO', 'VREMYA_ZHIZNI', 'ARTICLE'];
            $files = ['photo_id'];
            $filesMultiple = ['EXTERNAL_VIEW_ID', 'sketch_id', 'DOCUMENTATION_ID'];
            foreach($fields as $field) {
                $node->$field = trim($_REQUEST[$field]) ? trim($_REQUEST[$field]) : null;
            }
            foreach($files as $field) {
                if($fileId = FileService::upload($field)) {
                    $node->$field = $fileId;
                }
            }
            foreach($filesMultiple as $field) {
                if($fileId = FileService::uploadMultiple($field)) {
                    $node->$field = json_encode($fileId);
                }
            }
            $node->save();
        } else {
            header('Location: /equipments');
            die();
        }

        header('Location: /equipments?id=' . $node->id);
    }


    private function getDataByRequest(): array
    {
        $data = [];

        foreach($_REQUEST['DATA'] as $dataField){
            if (strpos($dataField['name'],"[]")){
				$data[$dataField['name']][] = $dataField['value'];
			}else{
				$data[$dataField['name']] = $dataField['value'];
			}
        }		

        return $data;
    }


}