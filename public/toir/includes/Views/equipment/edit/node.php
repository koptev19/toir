<div class="row mb-3">
	<div class='col-2'>Краткое название:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/string', ['name' => 'SHORT_NAME', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Заводской номер:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/string', ['name' => 'ZAVODSKOY_NOMER', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Документация:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/file_multiple', ['name' => 'DOCUMENTATION_ID', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Внешний вид:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/file_multiple', ['name' => 'EXTERNAL_VIEW_ID', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Фото:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/file', ['name' => 'photo_id', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Описание:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/html', ['name' => 'OPISANIE', 'node' => $node]); ?></div>
</div>
