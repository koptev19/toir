<div class='mb-1 row'>
    <div class='col-1'><a href="#" onclick="$('.filter').toggle(); return false;">Фильтр</a></div>
    <div class='col-2' id="clean-filter"><a href="work_planned_log.php">Сбросить фильтр</a></div>
</div>
<form method="get" action="work_planned_log.php" id='filterForm'>
    <div class='filter my-4' style="display:none;">
    
        <div class="mb-3 row">
            <div class='col-3'>Оборудование</div>
            <div class="col-9">
                <?php
                $this->view('components/select_equipment', [
                    'fieldName' => 'filter[EQUIPMENT_ID]',
                    'equipment' => $filter['EQUIPMENT_ID']
                ]);
                ?>
            </div>
        </div>

         <div class="mb-3 row">
            <div class='col-3'>Название операции</div>
            <div class="col-9">
                <input type="text" name="filter[%NAME]" class="form-control" value='<?php echo $filter['%NAME']?>' onchange="this.form.submit()">
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Периодичноcть</div>
            <div class="col-6">
                <input type="number" name="filter[PERIODICITY]" <?php echo ($filter['NO_PERIODICITY'] ? "disabled" : "") ?> class="form-control" value='<?php echo $filter['PERIODICITY']?>' onchange="this.form.submit()">
            </div>
			<div class="col-3 pt-2">
                <input onchange="check(this)" type="checkbox" value='true' name="filter[NO_PERIODICITY]" <?php echo ($filter['NO_PERIODICITY'] ? "checked" : "") ?>> без периода
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Тип операции</div>
            <div class="col-9">
                <select name="filter[TYPE_OPERATION]" class="form-control form-select" onchange="this.form.submit()">
                    <option value="">Не важно</option>
                    <?php foreach(Operation::getTypes() as $typeId => $typeName) { ?>
                        <option value="<?php echo $typeId; ?>" <?php if($typeId == $filter['TYPE_OPERATION']) echo "selected"; ?>><?php echo $typeName; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
<input type="hidden" name="limit" value="<?php echo $limit; ?>" id='filterLimit'>
<input type="hidden" name="page" value="1" id='filterPage'>
</form>
<script>
	function check(el){
		if ($(el).prop("checked")){
			$("input[name='filter[PERIODICITY]']").val("");
			$("input[name='filter[PERIODICITY]']").prop("disabled",true);
		}else{
			$("input[name='filter[PERIODICITY]']").prop("disabled",false);
        }
        el.form.submit();
	}	
    
$(document).ready(function() {
    $('#equipment').on('change', function() {
        if($(this).val() && $(this).val() != '<?php echo $filter['EQUIPMENT_ID'];?>') {
            this.form.submit();
        }
    });
})
</script>


