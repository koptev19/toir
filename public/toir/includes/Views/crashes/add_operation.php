
<form  method="post" action="crash_edit.php">
<input type="hidden" name="store_operation" value="1">
<input type="hidden" name="crash" value="" id="crash_id_hidden">

<div class="mb-3 row">
    <div class='col-2'>Название регламентной операции</div>
    <div class="col-10">
        <input type="text" name="NAME" class="form-control" required>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Комментарий по результату</div>
    <div class="col-10">
        <input type="text" name="COMMENT" class="form-control" required>
    </div>
</div>

<input value="Добавить операцию" type="submit" class='btn btn-primary'>
</form>
