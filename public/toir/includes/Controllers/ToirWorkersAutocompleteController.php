<?php

class ToirWorkersAutocompleteController extends ToirController
{

	public Function getWorkers(string $name)
	{
		$allWorkers = HighloadBlockService::getList(HIGHLOAD_WORKER_BLOCK_ID, [
			["%UF_NAME" => $name]
		]);

		$workers = [];
		foreach($allWorkers as $worker) {
			$workers[] = $worker['UF_NAME'];	
		}

		echo json_encode($workers);
	}
}