<h1 class='text-center mb-5'>Добавление аварии</h1>
<?php if(!empty($this->errors)) { ?>
    <div class="alert alert-danger" role="alert">
    <?php echo implode("<br>", $this->errors); ?>
    </div>
<? } ?>
<form  method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="save" value="1">
<div class="mb-3 row mb-4">
    <div class='col-2'>Оборудование</div>
    <div class="col-10">
        <?php
            $this->view('components/select_equipment', [
            ]);
            ?>
    </div>
</div>
<div class="mb-3 row mb-4">
    <div class='col-2'>Дата</div>
    <div class="col-10">
        <div>
            <input type='date' name="DATE" class='form-control' style="width:200px;" required value="<?php echo $_REQUEST['DATE']; ?>"> 
        </div>
    </div>
</div>
<div class="mb-3 row mb-4">
    <div class='col-2'>Время</div>
    <div class="col-10">
        <input type='time' name="TIME_FROM" class='form-control d-inline' style="width:200px;" required value="<?php echo $_REQUEST['TIME_FROM']; ?>"> 
        - 
        <input type='time' name="TIME_TO" class='form-control d-inline' style="width:200px;" required value="<?php echo $_REQUEST['TIME_TO']; ?>">
    </div>
</div>
<div class="mb-3 row mb-4">
    <div class='col-2'>Руководитель</div>
    <div class="col-10"><input type='text' name="OWNER" class='form-control' required value="<?php echo $_REQUEST['OWNER']; ?>"></div>
</div>

<input value="Добавить аварию " type="submit" class='btn btn-primary'>
</form>
