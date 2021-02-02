<h3 class='text-center mb-4'>Создание шаблона задач по приемке оборудования</h3>

<h4 class='text-center mb-4'>Шаг 1. Выбор оборудования</h4>

<form  method="post" action="">
<input type="hidden" name="save" value="1">
<input type="hidden" name="step" value="2">
<div class="mb-3 row">
    <div class='col-2'>Цех</div>
    <div class="col-10">
        <select id="workshop" name="workshop" required onchange="changeFuncline($(this).val());" class="form-control form-select">
            <option value="0" disabled selected hidden>Выберите цех</option>
            <?php foreach($workshops as $workshopId => $workshopName) { ?>
                <option  <?php if($_REQUEST['workshop']==$workshopId) {echo "selected";}?> value="<?php echo $workshopId; ?>"><?php echo $workshopName; ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<div class="mb-3 row">
    <div class='col-2'>Линия</div>
    <div class="col-10">
        <select id="line" name="LINE_ID" required onchange="changeFuncmechanism($(this).val());" class="form-control form-select">
            <option value="0" disabled selected hidden>Выберите линию</option>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Механизм</div>
    <div class="col-10">
        <select onchange="changeFuncnode($(this).val());" name="MECHANISM" class="form-control form-select" id='MECHANISM'>
            <option value="0" disabled selected hidden>Выберите механизм</option>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Узел</div>
    <div class="col-10">
        <select name="NODE" class="form-control form-select" id='NODE'>
            <option value="0" disabled selected hidden>Выберите узел</option>
        </select>
    </div>
</div>


<div class="text-center mt-5">
    <input value="Сохранить и перейти на шаг 2" type="submit" class='btn btn-primary'>
</div>
</form>