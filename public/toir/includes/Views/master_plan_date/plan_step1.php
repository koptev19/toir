<?php foreach ($this->dateProcesses as $dateprocess) {
	$lastDateProcess = $dateprocess; 
	if($dateprocess->STAGE == DateProcess::STAGE_PLAN_REJECTED){?>
    <div class='mb-5 alert alert-danger'><?php echo $dateprocess->COMMENT_REJECT; ?></div>

    <?php }
}?>
<form method="get" id="form" action="master_plan_date.php">
<input type="hidden" name="step" value='1'>
<input type="hidden" name="save" value='1'>
<input type="hidden" name="mode" value='plan'>
<input type="hidden" name="next" value='step2'>
<input type="hidden" name="service" value="<?php echo $this->service->ID; ?>">
<input type="hidden" name="date" value="<?php echo $this->date; ?>">
<?php 
	if(!DateProcessService::outOfDate($lastDateProcess)){
	$outOfDate = true;
	?>
		Укажите причину просрочки планирования<br>
		<textarea class="form-control" id="commentExpired" name='COMMENT_EXPIRED'><?php echo $cookie['COMMENT_EXPIRED']; ?></textarea>
		<br>
<?php } ?>

<div class="table-responsive mb-3 table-thead-fixed">
<table class="table table-bordered table-sm table-hover" id='table3'>
    <thead>
        <tr class='text-center'>
            <th><div>Наименование оборудования</div></th>
            <th><div>Название регламентной операции</div></th>
            <th><div>Тип операции</div></th>
            <th><div>Примечание</div></th>
            <th><div>Выполнить</div></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operationsInLine as $lineName => $operations) { ?>
        <tr>
            <td class='table-warning text-center' colspan=100%>
                <?php echo $lineName; ?>
            </td>
        </tr>
        <?php foreach($operations as $operation) { 
            $typeOperation = isset($cookie['TYPE_OPERATION']) ? $cookie['TYPE_OPERATION'][$operation->ID] : $operation->TYPE_OPERATION;
            $comment = isset($cookie['COMMENT']) ? $cookie['COMMENT'][$operation->ID] : $operation->COMMENT;
            $done = isset($cookie['done']) ? in_array($operation->ID, $cookie['done']) : true;
		    ?>
        <tr>
            <td><?php echo $operation->equipment ? $operation->equipment->NAME : ''; ?></td>
            <td><?php echo $operation->NAME; ?></td>
            <td>
                <select name="TYPE_OPERATION[<?php echo $operation->ID; ?>]" class='form-control form-select m-auto'>
                    <?php foreach(Operation::getTypes() as $key => $val) { ?>
                        <option value="<?php echo $key; ?>" <?php if($key == $typeOperation) echo "selected"; ?>><?php echo $val; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td class='text-center'>
                <input type="text" name="COMMENT[<?php echo $operation->ID; ?>]" value="<?php echo $comment; ?>" class='form-control w-100'>
            </td>
            <td class='text-center'>
            <?php if(is_a($operation, Operation::class)) { ?>
                <div class="custom-control custom-switch" style='cursor:pointer;'>
                    <input type="checkbox" class="custom-control-input" id="customCheck_<?php echo $operation->ID; ?>"  name="done[]" value='<?php echo $operation->ID; ?>' <?php echo $done ? "checked" : ""; ?> onchange="if (this.checked) $(this).parent().find('label').html('Да'); else $(this).parent().find('label').html('Нет');" style='cursor:pointer;'>
                    <label class="custom-control-label" for="customCheck_<?php echo $operation->ID; ?>" style='cursor:pointer;'><?php echo $done ? "Да" : "Нет"; ?></label>
                </div>
            <?php } else { ?>
                <input type="hidden" name="done[]" value='<?php echo $operation->ID; ?>'>
                Да
            <?php } ?>
            </td>
        </tr>
        <?php } ?>
    	
    <?php } ?>
    </tbody>
    <tfoot>
		 <tr>
            <td colspan=100%>
                <div class='text-center table-warning rounded border border-warning' style="width:380px;">
                    <button class="btn" onClick="addOperation(); return false">Добавить / редактировать / удалить операции</button>
                </div>
            </td>
        </tr>
    </tfoot>
        
</table>
</div>

<div class='mt-4 text-center  mx-auto' style="width:160px;">
    <table class="table table-hover table-sm table-borderless">
        <tr>
            <td class='p-0 '>
                <div class="table-warning rounded border border-warning">
                    <a href="#" class="btn" onClick="submitForm(); return false">Перейти на шаг 2</button>
		       </div>
            </td>
        </tr>
    </table>
</div>
</form>
<script>
	function submitForm(){
		<?php if($outOfDate){ ?>
			if(!$("#commentExpired").val()){
				alert("Укажите причину просрочки планирования!");
				$("#commentExpired").css("border","1px solid red");	
				$("#commentExpired").focus();
			}else{
				$("#form").submit();
			}
		<?php }else{ ?>
				$("#form").submit();
		<?php } ?>
	}

    function addOperation()
    {
        $("#form").attr('target', '_blank');
        $("#form").find('input[name="next"]').val('add_operation_group');
        $("#form").submit(); 
    }
</script>