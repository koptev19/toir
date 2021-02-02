<?php
class ToirDelOperationController extends ToirController
{

	public function delOperation()
	{
		$id = (int)$_REQUEST['id'];

		if ($id > 0) {
			if($plan = Plan::findAvailabled($id)) {
				$equipmentId = $plan->EQUIPMENT_ID;
				PlanService::delete($plan);
				header('Location:equipment.php?id=' . $equipmentId);
			} else if($operation = Operation::findAvailabled($id)) {
				$equipmentId = $plan->EQUIPMENT_ID;
				OperationService::deleteAndDeleteStop($operation);
				header('Location:index.php?workshop=' . $operation->WORKSHOP_ID . 'table2=noplan');
			}
		}
		
	}

}