<div class="row mb-3">
	<div class='col-2'>Краткое название:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/string', ['name' => 'SHORT_NAME', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Номер запасной части:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/string', ['name' => 'ZAVODSKOY_NOMER', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Артикул поставщика:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/string', ['name' => 'ARTICLE', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Номер детали:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/string', ['name' => 'INVENTARNYY_NOMER', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Фото:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/file', ['name' => 'photo_id', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Документация:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/file_multiple', ['name' => 'DOCUMENTATION_ID', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Описание:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/html', ['name' => 'OPISANIE', 'node' => $node]); ?></div>
</div>
