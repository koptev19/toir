<h5 class='text-center mb-5'>Редактирование приемки оборудования</h5>
<?php if(!empty($errors)) { ?>
    <div class="alert alert-danger" role="alert">
    <?php echo implode("<br>", $errors); ?>
    </div>
<?php } ?>
<form  method="post" action="accept.php" enctype="multipart/form-data">
<input type="hidden" name="action" value="save">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>">
<div class="mb-3 row mb-4">
    <div class='col-2'>Оборудование</div>
    <div class="col-10">
        <?php echo $equipment->getPath(" / ", false, true)." / ".$equipment->NAME; ?>
    </div>
</div>
<div class="mb-3 row mb-4">
    <div class='col-2'>Чек-лист</div>
    <div class="col-10">
        <textarea name="CHECKLIST" class='form-control' style="min-height:200px;"><?php echo $accept->CHECKLIST; ?></textarea>
        Для создания чеклиста введите текст.<br>
        Каждая новая строка - это новый пункт чеклиста
    </div>
</div>
<div class='row'>
	<div class='col-6'>
		<input value="Сохранить" type="submit" class='btn btn-primary'>
	</div>
	<div class='col-6 text-right'>
		<a href="accept.php"  type="" class="btn btn-outline-secondary" >Отмена</a>
	</div>
</div>
</form>
