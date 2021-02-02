<div class="row mb-3">
	<div class='col-2'>Краткое название:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/string', ['name' => 'SHORT_NAME', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Заводской номер:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/string', ['name' => 'ZAVODSKOY_NOMER', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Инвентарный номер:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/string', ['name' => 'INVENTARNYY_NOMER', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Дата ввода в эксплуатацию:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/date', ['name' => 'DATA_VVODA', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Внешний вид линии:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/file_multiple', ['name' => 'EXTERNAL_VIEW_ID', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Фото:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/file', ['name' => 'photo_id', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Схема линии:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/file_multiple', ['name' => 'sketch_id', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Документация на линию:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/file_multiple', ['name' => 'documentation_id', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Состояние линии:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/list', ['name' => 'SOSTOYANIE', 'node' => $node, 'items' => Equipment::$SOSTOYANIE_LINII]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Гарантия на линию до:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/date', ['name' => 'GARANTIYA_DO', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Описание:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/show/params/html', ['name' => 'OPISANIE', 'node' => $node]); ?></div>
</div>
