<div class='mb-1 row'>
    <div class='col-1'><a href="#" onclick="$('.filter').toggle(); return false;">Фильтр</a></div>
    <div class='col-2' id="clean-filter"><a href="log_downtime.php">Сбросить фильтр</a></div>
</div>
<form method="get" action="log_downtime.php" id='filterForm'>
    <div class='filter my-4' style="display:none;">
    
         <div class="mb-3 row">
            <div class='col-3'>Показать завершенные</div>
            <div class="col-9 pt-2">
                <input onchange="this.form.submit()" type="checkbox" value='true' name="filter[SHOW_ALL]" <?php echo ($filter['showAll'] ? "checked" : "") ?>>
            </div>
        </div>
		 
		 <div class="row mb-3">
            <div class='col-3'>Дата</div>
            <div class="col-4">
                <input type="date" name="filter[DATE_FROM]" value="<?php echo $filter['DATE_FROM']?>" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-1 pt-2 text-center">
                -
            </div>
            <div class="col-4">
                <input type="date" name="filter[DATE_TO]" value="<?php echo $filter['DATE_TO']?>" class="form-control" onchange="this.form.submit()">
            </div>
        </div>
		
		
		<div class="mb-3 row">
            <div class='col-3'>Оборудование</div>
            <div class="col-9">
                <div id="selectedEquipment" class="border rounded p-2" style="cursor:pointer;">
					<input id='equipment' value="<?php echo $filter['EQUIPMENT_ID']?>" name="filter[EQUIPMENT_ID]" type="hidden" onChange="equipmentFieldChange(this)">
				</div>
            </div>
        </div>


         <div class="mb-3 row">
            <div class='col-3'>Станок</div>
            <div class="col-9">
                <input type="text" name="filter[%MACHINE]" class="form-control" value='<?php echo $filter['%MACHINE']?>' onchange="this.form.submit()">
            </div>
        </div>

		<div class="mb-3 row">
            <div class='col-3'>Мастер</div>
            <div class="col-9">
                <input type="text" name="filter[%MASTER]" class="form-control" value='<?php echo $filter['%MASTER']?>' onchange="this.form.submit()">
            </div>
        </div>

         <!--<div class="mb-3 row">
            <div class='col-3'>Служба</div>
            <div class="col-9">
                <select name="filter[SERVICE_ID]" class="form-control form-select">
                    <option value="">Не важно</option>
                    <?php foreach($services as $service) { ?>
                        <option value="<?php echo $service->ID; ?>" <?php if($service->ID == $filter['SERVICE_ID']) echo "selected"; ?>><?php echo $service->NAME; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div> -->

        <div class="mb-3 row">
            <div class='col-3'>Цех</div>
            <div class="col-9">
                <select name="filter[WORKSHOP_ID]" class="form-control form-select" onchange="this.form.submit()">
                    <option value="">Все цеха</option>
                    <?php foreach(UserToir::current()->availableWorkshops as $key => $workshop) { ?>
                        <option value="<?php echo $key; ?>" <?php if($workshop->ID == $filter['WORKSHOP_ID']) echo "selected"; ?>><?php echo $workshop->NAME; ?></option>
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


