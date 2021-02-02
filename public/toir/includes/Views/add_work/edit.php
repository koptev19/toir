<?php 
$this->view('_header', ['title' => "Редактирование операции"]);

if($_SESSION['add_work_errors'] && is_array($_SESSION['add_work_errors']) && count($_SESSION['add_work_errors'])){
    echo '<div class="alert alert-danger">';
	echo implode("<br>", $_SESSION['add_work_errors']);
	echo "</div>";
    $_SESSION['add_work_errors'] = null;
}	  
?>

<h1 class='text-center mb-5'>Редактирование операции без даты</h1>
<form  method="post" action="add_work.php">
<input type="hidden" name="action" value="save">
<input type="hidden" name="work_id" value="<?php echo $work->ID; ?>">

<div class="mb-3 row">
    <label class='col-2 col-form-label font-weight-bold'>Оборудование</label>
    <label class='col-10 col-form-label font-weight-bold'><?php echo $this->equipment->path(false); ?></label>
</div>

<div class="mb-3 row">
    <label class='col-2 col-form-label'>Служба</label>
    <div class="col-10">
        <select name="SERVICE_ID" required class="custom-select form-select">
            <option value="" disabled selected hidden>Выберите</option>
            <?php foreach($services as $service) { ?>
                <option <?php echo ($work->SERVICE_ID == $service->ID )? "selected"  : ""; ?> value="<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Название операции</div>
    <div class="col-10">
        <input type="text" name="NAME" value='<?php echo $work->NAME; ?>' class="form-control" required>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Тип операции</div>
    <div class="col-10">
        <select name="TYPE" required class="custom-select form-select">
            <option value="" disabled selected hidden>Выберите</option>
            <?php foreach(Operation::getTypes() as $typeId => $typeName) { ?>
                <option <?php echo ($work->TYPE == $typeId )? "selected"  : ""; ?> value="<?php echo $typeId; ?>"><?php echo $typeName; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Рекомендации</div>
    <div class="col-10">
        <input type="text" value='<?php echo $work->RECOMMENDATION; ?>' name="RECOMMENDATION" class="form-control">
    </div>
</div>

<input value="Сохранить" type="submit" class='btn btn-primary'>
</form>

<?php $this->showFooter(); ?>
