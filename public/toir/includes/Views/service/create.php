<h3>Добавление новой службы</h3>
<form action="" method="post">
    <input type="hidden" name="ACTION" value="store">
    <div class="row mb-3 mt-3">
        <div class='col-4 col-md-2'><label class="col-form-label">Наименование:</label></div>
        <div class="col-10"><input type="text" name="NAME" value="" class='form-control' required></div>
    </div>
    <div class="row mb-3 mt-3">
        <div class='col-4 col-md-2'><label class="col-form-label">Сокращенное наименование:</label></div>
        <div class="col-10"><input type="text" name="SHORT_NAME" value="" class='form-control'></div>
    </div>
	 <div class="row mb-3 mt-3">
        <div class='col-4 col-md-2'><label class="col-form-label">Руководитель службы:</label></div>
        <div class="col-8 col-md-10">
            <select name="MANAGER_ID" class="form-control form-select">
                <?php foreach(UserService::getList() as $user) { ?>
                    <option value="<?php echo $user->id?>" ><?php echo $user->name; ?></option>
                <?php } ?>
            </select>            
        </div>
    </div>
    <div class='mt-4'>
        <input type="submit" value="Добавить" class='btn btn-primary'>
    </div>
</form>