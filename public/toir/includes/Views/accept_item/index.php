<style>
.custom-control-label:before, .custom-control-label:after, .form-check-input{ height:2rem; width:2rem;}
textarea.form-control {font-size:2rem; height:65px;}
#commentText {height:200px;}
</style>

<h1 class='text-center mb-5'>Приемка оборудования</h1>

<form  method="post" action="" enctype="multipart/form-data" id='form'>
	<input type="hidden" name="action" value="store">
	<input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>">
	<div class="row mb-4 h2">
		<div class='col-3'></div>
		<div class="col-9"><?php echo $equipment->getPath(" / ", false, true) ." / " .$equipment->NAME; ?></div>
	</div>
	<div class="row mb-5 h2">
		<div class='col-3'>Фамилия</div>
		<div class="col-9">
			<textarea id="USER_NAME" name="USER_NAME" class='form-control form-control-lg border-dark' required></textarea>
		</div>
	</div>
	<div id='buttons1' style="display:none;">
		<input value="Приемка оборудования" type="button" class='btn btn-success btn-lg mr-3 h1' onclick="$(this).parent().hide(); $('#checklist').show();">
		<input value="Замечания в процессе работы" type="button" class='btn btn-danger btn-lg mr-3 h1' onclick="$(this).parent().hide(); $('#comments').show();">
	</div>
	<div class="mb-3 row h2" style="display:none;" id="checklist">
		<?php if($accept->CHECKLIST){?>
			<div class='col-3'>Чек-лист</div>
			<div class="col-9">
			<?php foreach (explode(PHP_EOL,$accept->CHECKLIST) as $key => $check){ ?>
				<div class="form-check custom-control custom-checkbox mb-5">
					<input class="form-check-input custom-control-input checklist" type="checkbox" id="flexCheckChecked-<?php echo $key; ?>">
					<label class="form-check-label custom-control-label pl-4" for="flexCheckChecked-<?php echo $key; ?>"><?php echo $check; ?></label>
				</div>
			<?php } ?>	
			</div>
		<?php } ?>
	</div>

	<div id='comments' style='display:none'>
		<div class="mb-3 row mb-4 h2">	
			<div class='col-3'>Комментарий</div>
			<div class="col-9">
				<textarea name="COMMENT" id='commentText' class='form-control border-dark'></textarea>
			</div>
		</div>	
		<div class="mb-3 row mb-4 h2">	
			<div class='col-3'>Файлы</div>
			<div class="col-9">
				<input type="file" multiple name="files[]" class="form-control" />
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<input value="Сохранить" type="submit" class='btn btn-primary btn-lg'>
			</div>
			<div class="col-6 text-right">
				<a href='#'  onclick='addComment(); return false;' type="" class='btn btn-outline-secondary btn-lg'>Отмена</a>
			</div>
		</div>
	</div>

	<div id='buttons' style='display:none'>
		<input value="Нет замечаний" type="submit" class='btn btn-success btn-lg mr-3 h1'>
		<a href='#'  OnClick='addComment(); return false;' type="" class='btn btn-danger btn-lg h1'>Есть замечания</a>
	</div>
</form>


<script>
function addComment(){
	$('#comments').toggle();
	$('#buttons').toggle();
	$('#commentText').prop('required', !$('#commentText').prop('required'));
}

function check(){
	var show = true;
	$(".checklist").each(function(index, item){
		show = ($(item).prop("checked") && show);
		console.log($(item).prop("checked"));
	});	
	return show && $("#USER_NAME").val().length > 1;
}

$(document).ready(function() {
	$("#USER_NAME").bind('keyup change', function(){
		if($("#USER_NAME").val().length>1){
			$("#buttons1").show();	
		}else{
			$("#buttons1").hide();	
		}
	});

	$(".checklist").change(function(){
		if(check()) {
			$('#buttons').show();			
		} else {
			$('#buttons').hide();
			$('#comments').hide();
		}
	});	
});
</script>