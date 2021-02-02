<?php
global $USER;
?>
<h3 class='text-center'>
    Журнал приемки оборудования
</h3>

<table class="table table-bordered table-sm">
    <thead>
        <tr class='text-center'>
            <th>Оборудование</th>
			<th>Фамилия</th>
            <th>Дата приемки</th>
            <th>Комментарий и фото</th>
            <th>Заявки на ремонт</th>
         </tr>
    </thead>
    <tbody>
    <?php foreach($acceptItems as $acceptItem) { 
        $class = $selectedReceiving && $selectedReceiving->ID ==  $acceptItem->ID ? "table-info" : '';
		?>
        <tr class="<?php echo $class; ?>">
            <td><?php echo $acceptItem->EQUIPMENT_ID ? $equipments[$acceptItem->EQUIPMENT_ID]->path() : ''; ?></td>
			<td><?php echo $acceptItem->USER_SECOND_NAME; ?></td>
            <td class='text-center'><?php echo date("d.m.Y H:i:s", strtotime($acceptItem->created_at)); ?></td>
            <td>
			    <?php echo $acceptItem->COMMENT; ?>
                <div>
                    <?php foreach(json_decode($acceptItem->files ?? "[]") as $fileId) { 
                        $file = File::find($fileId);
                        ?>
                        <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><img src="<?php echo FileService::getUrl($file); ?>" width=150 class="mb-3 mr-3"></a>
                    <?php } ?>
                </div>
			</td>	
            <td class='text-center'>
            <?php if($acceptItem->STAGE == AcceptItem::STAGE_NEW) { ?>
                <a href="repair_request.php?log_receiving_id=<?php echo $acceptItem->ID; ?>" target=_blank class='text-danger'>Привлечь службу</a><br><br>
                <a onClick='done(<?php echo $acceptItem->ID; ?>); return false' href="#" class='text-danger'>Привлечение службы не требуется</a><br><br>
            <?php } ?>

            <?php if($acceptItem->STAGE == AcceptItem::STAGE_DONE) { ?>
                <?php if(!$acceptItem->COMMENT) { ?>
                    <div class='text-success'>Замечаний нет</div>
                <?php } else { ?>
                    <?php if(count($acceptItem->serviceRequests)) { ?>
                        <div>Заявки на ремонт:<br>
                        <?php foreach($acceptItem->serviceRequests as $serviceRequest) { ?>
                            <a href="service_request.php?selected_id=<?php echo $serviceRequest->id; ?>"> <?php echo $serviceRequest->id; ?></a><br>
                        <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div>Службы не привлекались</div><br>
						<span class='text-info font-italic'><?php echo $acceptItem->COMMENT_DONE; ?><span>
                    <?php } ?>
                <?php }  ?>
            <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<div class="modal fade" tabindex="-1" id='commentDone'>
    <div class="modal-dialog  modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

			<div class='modal-body'>
				<form action="" type='POST'>
				<input type=hidden name='action' value='done'>
				<input type=hidden name='id' value='' id='comment_done_id'>	
				<div class="row mb-4">
					<div class="col-3">
						 Укажите причину:
					</div>
					<div class="col-9">
						<textarea class='form-control' id='comment_done' name='COMMENT_DONE' required></textarea>
					</div>
				</div>
				<button type="submit" class="btn btn-primary">сохранить</button>
				</form>
			</div>	
		</div>
		
	</div>
</div>

<?php
$this->view('components/paginate', [
    'maxPage' => $maxPage,
])
?>

<script>
	function done(id){
		$('#comment_done_id').val(id);
		$('#comment_done').val("");
		$('#commentDone').modal('show');
		$("#commentDone").appendTo("body")
	}

	$(document).ready(function() {
		$('#commentDone').on('shown.bs.modal', function () {
		  $('#comment_done').trigger('focus')
		});
	});
</script>

