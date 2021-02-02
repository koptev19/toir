<h1 class='text-center mb-5'>Шаг 1 - редактирование данных</h1>

<form  method="post" action="">
<input type="hidden" name="update" value="1">
<input type="hidden" name="step" value="2">
    <div class="mb-3 row">
        <div class='col-2'>Оборудование</div>
        <div class="col-10">
        <label class="border p-2 w-100"><?php echo $this->operation->equipment->path(); ?></label>
        </div>
    </div>

    <div class="mb-3 row">
        <div class='col-2'>Название регламентной операции</div>
        <div class="col-10">
            <input type="text" name="NAME" class="form-control" value="<?php echo $this->operation->NAME; ?>">
        </div>
    </div>

    <div class="mb-3 row">
        <div class='col-2'>Тип операции</div>
        <div class="col-10">
            <select name="TYPE_OPERATION" required class="form-control form-select">
                <option value="0" disabled selected hidden>Выберите</option>
                <?php foreach(Operation::getTypes() as $typeId => $typeName) { ?>
                    <option value="<?php echo $typeId; ?>" <?php if($typeId == $this->operation->TYPE_OPERATION) echo "selected"; ?>><?php echo $typeName; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <div class='col-2'>Рекомендации</div>
        <div class="col-10">
            <input type="text" name="RECOMMENDATION" class="form-control" value="<?php echo $this->operation->RECOMMENDATION; ?>">
        </div>
    </div>

<?php if (is_a($this->operation, Plan::class)) { ?>

    <div class="mb-3 row">
        <div class='col-2'>Вид ТО</div>
        <div class="col-10">
            <select name="TYPE_TO" required class="form-control form-select">
                <?php foreach(Plan::getTypesTo() as $typeId => $typeName) { ?>
                    <option value="<?php echo $typeId; ?>" <?php if($typeName == $this->operation->TYPE_TO) echo "selected"; ?>><?php echo $typeName; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <div class='col-2'>Периодичность</div>
        <div class="col-10">
            <input type="number" min="1" name="PERIODICITY" class="form-control" value="<?php echo $this->operation->PERIODICITY; ?>">
        </div>
    </div>
<?php } ?>

<input value="Сохранить" type="submit" class='btn btn-primary'>
</form>

