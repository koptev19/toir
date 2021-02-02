<h5 class='text-center mb-5'>Приемка "<?php echo $accept->NAME ?>"</h5>
<form  method="post" action="accept.php" enctype="multipart/form-data">
<input type="hidden" name="action" value="apply_store">
<input id='noComment' type="hidden" name="noComment" value="1">
<input type="hidden" name="id" value="<?php echo $accept->ID ?>">
<div class="mb-3 row mb-4">
    <div class='col-2'>Фамилия</div>
    <div class="col-10">
		<input type='text' name="USER_NAME" value='' class='form-control' required>
	</div>
</div>
<div id='comments' style='display:none'>
	<div class="mb-3 row mb-4">	
		<div class='col-2'>Комментарий</div>
		<div class="col-10">
		    <textarea name="COMMENT" id='commentText' class='form-control'></textarea>
		</div>
	</div>	
    <div class="mb-3 row mb-4">	
        <div class='col-2'>Файлы</div>
        <div class="col-10">
            <?
            $GLOBALS["APPLICATION"]->includeComponent(
                            'bitrix:disk.uf.file',
                            '.default',
                            [
                                'EDIT' => 'Y',
                                'PARAMS' => [
                                    "HIDE_CHECKBOX_ALLOW_EDIT"=>true,
                                    'arUserField'=>['VALUE'=>$arr,'FIELD_NAME'=>"FILE[]","MULTIPLE"=>"Y"]
                                ],
                                'RESULT' => $result,
                                'DISABLE_LOCAL_EDIT' => true,
                                'DISABLE_CREATING_FILE_BY_CLOUD' => true,
                            ],
                            $component,
                            ['HIDE_ICONS' => 'Y']
            );?>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
	        <input value="Сохранить" type="submit" class='btn btn-primary'>
        </div>
        <div class="col-6 text-right">
	        <a href='#'  OnClick='addComment(); return false;' type="" class='btn btn-secondary'>Отмена</a>
        </div>
    </div>
</div>

<div id='buttons'>
	<input value="Нет замечаний" type="submit" class='btn btn-primary mr-5'>
	<a href='#'  OnClick='addComment(); return false;' type="" class='btn btn-outline-secondary'>Есть замечания</a>
</div>
</form>

<script>
	function addComment(){
		$('#comments').toggle();
		$('#buttons').toggle();
		let comm = $('#noComment').val() === "1"  ? "0" : "1";
		$('#noComment').val(comm);
		$('#commentText').prop('required', !$('#commentText').prop('required'));
	}
</script>