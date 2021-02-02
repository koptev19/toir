<h3>Редактирование службы</h3>
<form action="" method="post">
    <input type="hidden" name="ACTION" value="update">
    <input type="hidden" name="ID" value="<?php echo $service->ID; ?>">
    <div class="row mb-2 mt-5">
        <div class='col-4 col-md-2'><label class="col-form-label">Наименование:</label></div>
        <div class="col-10"><input type="text" name="NAME" value="<?php echo $service->NAME; ?>" class='form-control' required></div>
    </div>
    <div class="row mb-3 mt-3">
        <div class='col-4 col-md-2'><label class="col-form-label">Сокращенное наименование:</label></div>
        <div class="col-10"><input type="text" name="SHORT_NAME" value="<?php echo $service->SHORT_NAME; ?>" class='form-control'></div>
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
    <div class='mt-4 btn-toolbar justify-content-between'>
        <input type="submit" value="Сохранить" class='btn btn-primary'>
		<a href="?ACTION=delete&ID=<?php echo $service->ID; ?>" onclick="return confirm('Удалить службу <?php echo $service->NAME; ?> ?')" class="btn btn-outline-secondary float-right">Удалить</a>
    </div>
</form>
<script>

</script>
