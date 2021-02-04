<form action="/equipments" enctype="multipart/form-data" method="POST">
	<div class="error alert alert-danger" role="alert" style="display:none;"></div>
	<input type=hidden name="ID" value="<?php echo $node->ID; ?>">
	<input type=hidden name="ACTION" value="update">
	<div class='mb-4'>
		<span class='h5'><?php echo $node->NAME; ?> (<?php echo $node->TYPE; ?>)</span>
	</div>

	<div class="row mb-3">
		<div class='col-2'>Наименование:</div>
		<div class="col-10"><input type="text" name="NAME" value="<?php echo $node->NAME;  ?>" class='form-control' required></div>
	</div>
	<?php
	if($node->TYPE == Equipment::TYPE_WORKSHOP) {
		$this->view('equipment/edit/workshop', ['node' => $node]);
	}
	if($node->TYPE === Equipment::TYPE_LINE) {
		$this->view('equipment/edit/line', ['node' => $node]);
	}
	if($node->TYPE === Equipment::TYPE_MECHANISM) {
		$this->view('equipment/edit/mechanism', ['node' => $node]);
	}
	if($node->TYPE === Equipment::TYPE_NODE) {
		$this->view('equipment/edit/node', ['node' => $node]);
	}
	if($node->TYPE === Equipment::TYPE_DETAIL) {
		$this->view('equipment/edit/detail', ['node' => $node]);
	}
	?>

	<div class='mt-4'>
		<input type="submit" class='btn btn-primary' value="Сохранить">
		<a onClick="showNode(<?php echo $node->ID; ?>); return false" class='btn btn-outline-secondary ml-5'>Отмена</a>
	</div>
</form>