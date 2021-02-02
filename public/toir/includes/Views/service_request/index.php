<?php
global $USER;

$selected_id = $_REQUEST['selected_id'] ? (is_array($_REQUEST['selected_id']) ? $_REQUEST['selected_id'] : [$_REQUEST['selected_id']]) : [];
?>

<style>

@keyframes spinner-border {
  to { transform: rotate(360deg); }
}

.spinner-border {
  display: inline-block;
  width: 2rem;
  height: 2rem;
  vertical-align: text-bottom;
  border: .25rem solid #000000;
  border-right-color: transparent;
  border-radius: 50%;
  animation: spinner-border .75s linear infinite;
}

</style>

<h3 class='text-center'>
    Журнал заявок на ремонт
</h3>

<table class="table table-bordered table-sm">
    <thead>
        <tr class='text-center'>
            <th>№</th>
            <th>Создал заявку</th>
            <th>Служба</th>
            <th>Оборудование</th>
            <th>Дата и время</th>
            <th>Комментарий и фото</th>
            <th>Комментарий механика</th>
            <th>Принято</th>
            <th>Взял в работу</th>
            <th>Операции</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($serviceRequests as $serviceRequest) {
        $class = in_array($serviceRequest->ID, $selected_id) ? "table-info" : '';
        ?>
        <tr class="<?php echo $class; ?>">
            <td><?php echo $serviceRequest->ID; ?>
			<div style='height:6px'></div>
			    <?php if($serviceRequest->CRASH_ID) {?>
                    <a href="crashes.php?workshop=<?php echo $serviceRequest->WORKSHOP_ID; ?>&crash=<?php echo $serviceRequest->CRASH_ID; ?>">Авария</a>
                <?php } elseif($serviceRequest->RECEIVING_ID) { ?>
                    <a href="log_receiving.php?receiving=<?php echo $serviceRequest->RECEIVING_ID; ?>">Приемка</a> 
                <?php } else { ?>
                    <?php echo $serviceRequest->NAME; ?>
                <?php } ?>
			</td>
            <td><?php echo $serviceRequest->author->fullname; ?></td>
            <td><?php echo $serviceRequest->service->NAME; ?></td>
            <td><?php echo $serviceRequest->EQUIPMENT_ID ? $equipments[$serviceRequest->EQUIPMENT_ID]->path() : '' ?></td>

            <td class='text-center'><?php echo date("d.m.Y H:i:s", strtotime($serviceRequest->created_at)); ?></td>
            <td>
				<?
				if($serviceRequest->RECEIVING_ID){
					echo $receivings[$serviceRequest->RECEIVING_ID]->COMMENT; ?>
                    <div>
                    <?php foreach(json_decode($receivings[$serviceRequest->RECEIVING_ID]->files ?? "[]") as $fileId) { 
                        $file = File::find($fileId);
                        ?>
                        <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><img src="<?php echo FileService::getUrl($file); ?>" width=150 class="mb-3 mr-3"></a>
                    <?php } ?>
                </div>
				<?php }
				?>
			</td>
            <td><?php echo $serviceRequest->COMMENT; ?></td>
            <td class='text-center'>
            <?php if ($serviceRequest->histories) { ?>
                <div class="text-success">Принято</div>
            <?php } else { ?>
                <div class="text-danger">Не принято</div>
                <?php if(!$serviceRequest->CRASH_ID) { ?>
                    <div><a href="add_operation_group.php?service=<?php echo $serviceRequest->SERVICE_ID; ?>&source=<?php echo Operation::SOURCE_GROUP_SERVICE_REQUIEST ?>&service_request=<?php echo $serviceRequest->ID; ?>" class="btn btn-outline-primary my-3" target=_blank>Операция c остановкой линии</a></div>
                <?php } ?>
                <div><a href="add_history_group.php?service_request=<?php echo $serviceRequest->ID; ?>" class="btn btn-outline-primary" target=_blank>Операция без остановки линии</a></div>
            <?php } ?>
            </td>
            <td><?php echo $serviceRequest->executor->fullname; ?></td>
            <td>
                <?php if($serviceRequest->histories) { ?>
                    <a href="#" onclick="showOperations(<?php echo $serviceRequest->ID; ?>); return false;">Операции</a>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php
$this->view('components/paginate', [
    'maxPage' => $maxPage,
])
?>

<div class="modal fade" tabindex="-1" id='operations-detail'>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Операции по заявке № <span></span></h5>
            </div>
            <div class="modal-body" id='modal-wait'>
                <div class='text-center mb-4'>Подождите...</div>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border " role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-body" id='modal-operations' style="display:none;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>

function showOperations(service_request_id) 
{
    $('#operations-detail h5 span').html(String(service_request_id));
    $('#operations-detail').modal('show');
	$("#operations-detail").appendTo("body")
    $('#modal-wait').show();
    $('#modal-operations').hide();

	$.ajax({
	    type: "POST",
	    url: "service_request.php",
	    data: {
			service_request_id: service_request_id,
		},
	    success: function ( data ) {
            $('#modal-operations').html(data);
            $('#modal-wait').hide();
            $('#modal-operations').show();
	    }
	 });
}

</script>