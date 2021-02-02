<div class="row mb-3">
	<div class='col-2'>Краткое название:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/string', ['name' => 'SHORT_NAME', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Начальник цеха:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/user', ['name' => 'NACHALNIK_TSEKHA', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Механик цеха:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/user', ['name' => 'MECHANIC_ID', 'node' => $node, 'required' => true]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Внешний вид цеха:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/file_multiple', ['name' => 'EXTERNAL_VIEW_ID', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Фото:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/file', ['name' => 'photo_id', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>План цеха:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/file_multiple', ['name' => 'sketch_id', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>ИД папки документация:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/int', ['name' => 'ID_PAPKI_DOKUMENTATSIYA', 'node' => $node]); ?></div>
</div>
<div class="row mb-3">
	<div class='col-2'>Описание:</div>
	<div class="col-10 pl-3"><?php $this->view('equipment/edit/params/html', ['name' => 'OPISANIE', 'node' => $node]); ?></div>
</div>

