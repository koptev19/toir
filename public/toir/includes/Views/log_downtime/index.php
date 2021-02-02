<h4 class='text-center'>
    Журнал простоев
	<a href="#" class='ml-3' onclick="self.print(); return false;"><img src="./images/print.svg"></a>
</h4>

<?php
$this->view('log_downtime/filter', compact('limit', 'services', 'filter'));
?>

<div class="table-responsive mb-3">
<table class="table table-bordered table-sm table-hover">
    <thead>
        <tr class='text-center'>
            <th>Дата</th>
			<th>Смена</th>
			<th>Цех</th>
			<th>Станок</th>
            <th>Начало простоя</th>
			<th>Конец простоя</th>
			<th>Продолжительность</th>
			<th>Мастер смены</th>
			<th>Комментарий мастера смены</th>
			<th>Уточнение ремонтной службы</th> 
			<th>Мероприятия</th>
			<th>Статус</th>	
         </tr>
    </thead>
    <tbody>
    <?php foreach($downtimes as $downtime) {?>
        <tr>
            <td class="text-center"><?php echo $downtime->DATE ?></td>
			<td>
				<?php 
					
					$time = strtotime("01.01.2001 ".$downtime->TIME_FROM);
					if( $time >= strtotime("01.01.2001 08:00") && $time < strtotime("01.01.2001 16:30") ){
						echo "1 смена";
					}elseif ($time >= strtotime("01.01.2001 16:30") && $time < strtotime("02.01.2001 01:00")) {
						echo "2 смена";	
					}elseif ($time >= strtotime("01.01.2001 01:00") && $time < strtotime("01.01.2001 08:00")) {
						echo "3 смена";	
					}
				?>
			</td>
            <td><?php
					if($downtime->WORKSHOP_ID){
						echo Equipment ::find($downtime->WORKSHOP_ID)->NAME;
					}
				?>	
			</td>
			<td><?php
					if($downtime->EQUIPMENT_ID){
						echo Equipment ::find($downtime->EQUIPMENT_ID)->path();
						echo "<br><i>".$downtime->MACHINE."</i>";
					}else{
						echo $downtime->MACHINE;
					}	
				?>	
			</td>
            <td class="text-center"><?php echo $downtime->TIME_FROM; ?></td>
            <td class="text-center"><?php echo $downtime->TIME_TO; ?></td>
			<td class="text-center">
				<?php
				$minutes = (strtotime("01.01.2001 ".$downtime->TIME_TO) - $time)/60;
				echo intval($minutes / 60)." ч. ".intval($minutes % 60)." мин.";
				?>
			</td>
            <td><?php echo $downtime->MASTER;?></td>
			<td><?php echo $downtime->COMMENT;?></td>
			<td><?php echo $downtime->COMMENT_SERVICE ?></td>
			<td></td>
			<td class="text-center">
                <?php if($downtime->STAGE == Downtime::STAGE_NEW) { ?>
                    <a href="#" class="btn btn-primary" onclick="changeService(<?php echo $downtime->ID ?>);">Подтвердить службу</a>
                <?php } ?>
				
				<?php if($downtime->STAGE == Downtime::STAGE_SERVICE) { ?>
                    <form method=post action="">
					<input type="hidden" name="action" value="changeEquipment"> 
					<input type="hidden" name="id" value="<?php echo $downtime->ID ?>"> 	
					<input type="hidden" onChange="equipmentFieldChange(this)" name="EQUIPMENT_ID"  class="equipment-select-input">
					<br><br>
					<button type="submit" style='display:none' class="btn btn-primary">Выбрать</button>
					</form>
				<?php } ?>

				<?php if($downtime->STAGE == Downtime::STAGE_EQUIPMENT) { ?>
                    <a href="#" class="btn btn-primary" onclick="addComment(<?php echo $downtime->ID ?>);">Добавьте уточнение</a>
                <?php } ?>

				<?php if($downtime->STAGE == Downtime::STAGE_COMMENT) { ?>
                    <a href="#" class="btn btn-primary" onclick="$(this).hide(); $(this).next().show(); return false;">Назначьте мероприятия</a>
					<div style='display:none' id='addAction<?php echo $downtime->ID; ?>"'>
						<a href="add_operation_group.php?source=downtime&service=<?php echo $downtime->SERVICE_ID; ?>&downtime_id=<?php echo $downtime->ID ?>" class="btn btn-primary" target='_blank'>Операции с остановкой линии</a><br><br>
						<a href="add_history_group.php?downtime_id=<?php echo $downtime->ID ?>" class="btn btn-primary" target="_blank">Операции без остановки линии</a><br><br>
						<a href="?action=stageOperations&id=<?php echo $downtime->ID ?>" class="btn btn-primary" ">Операции больше не требуются</a>
					</div>
                <?php } ?>

				<?php if($downtime->STAGE == Downtime::STAGE_OPERATIONS) { ?>
                    <a href="?action=done&id=<?php echo $downtime->ID ?>" class="btn btn-primary">Завершить</a>
                <?php } ?>

				<?php if($downtime->STAGE == Downtime::STAGE_DONE) { ?>
	                    Завершено
                <?php } ?>
            
			</td>
           </tr>
    <?php } ?>
    </tbody>
</table>
</div>

<?php $this->view('components/select_equipment', ["multiply"=>true]); ?>

<div class="modal fade" tabindex="-1" id='serviceSelect'>
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

			<div class='modal-body'>
				<form method=post action="" id='form'>
				<input type='hidden' id='service_id' name='id'>
				<input type='hidden' id='action' name='action'>
				<div class="row mb-4" id="buttons">
					<div class="col-6">
						<button type="button" onClick="showServiceList();" class="btn btn-primary">Сменить службу</button>
					</div>
					<div class="col-6">
						<a href="#" onClick="$('#action').val('confirmService'); $('#form').submit();" class="btn btn-primary">Подтверждаю</a>
					</div>
				</div>
				<div id="serviceList" class="mb-3" style="display:none">
					<select class="form-control form-select" name="service_id">
					<?php 
					$services = Service::all();
					foreach($services as $key=>$service){?> 
						<option value=<?php echo $key; ?>><?php echo $service->NAME; ?></option>	
					<?}?>
					</select><br>
					<button type="button" onClick="$('#action').val('changeService'); $('#form').submit();" class="btn btn-primary">Выбрать</button>
				</div>
			</form>
			</div>	
		</div>
		
	</div>
</div>



<div class="modal fade" tabindex="-1" id='commentService'>
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

			<div class='modal-body'>
				<form method=post action="" id='form'>
				<input type='hidden' id='comment_id' name='id'>
				<input type='hidden' name='action' value='changeComment'>
				Уточнение:<br>
				<textarea required name="COMMENT_SERVICE" class="form-control"></textarea>
				<br>
				<div class="row mb-4" id="buttons">
					<div class="col-6">
						<button type="submit" class="btn btn-primary">Сохранить</button>
					</div>
				</div>
				
			</form>
			</div>	
		</div>
		
	</div>
</div>


<script>
	
	function equipmentFieldChange(el){
		var id=$(el).attr('id');
		$('a[data-lnk="'+id+'"]').removeClass("btn btn-primary");
		$(el).parent().find("button[type='submit']").show();
	}	
	
	function changeService(id){
		$("#serviceList").hide();
		$("#buttons").show();
		$("#service_id").val(id);
		$("#serviceList").hide();
		$("#serviceSelect").modal("show");
	}

	function showServiceList(){
		$("#serviceList").show();
		$("#buttons").hide();
	}

	function addComment(id){
		$("#comment_id").val(id);
		$("#commentService").modal("show");
	}

	$( document ).ready(function() {
		$(".equipment-select-modal").addClass("btn btn-primary");
		makeEquipmentHref($("#equipment"));
	});
</script>
