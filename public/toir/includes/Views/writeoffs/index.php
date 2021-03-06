<?php
$services = UserToir::current()->availableServices;
?>
<div class="row">
    <div class="col-4 mb-5">
        <?php if(count($services) > 1) { ?>
            <button type="button" class='me-3 btn btn-outline-primary' data-bs-toggle="modal" data-bs-target="#add-writeoff">Добавить списания ТМЦ</button>
        <?php } else { ?>
            <a href="instant_writeoff.php?service=<?php echo reset($services)->ID; ?>" class='me-3 btn btn-outline-primary'>Добавить списания ТМЦ</a>
        <?php } ?>
    </div>
    <div class="col-3 text-center h3">
        Журнал списания ТМЦ
    </div>
    <div class="col-5">
        <button type="button" class='ml-3 btn btn-outline-primary' data-bs-toggle="modal" data-bs-target="#print-akt">Акт списания ТМЦ</button>
    </div>
</h3>

<?php
$this->view('writeoffs/filter');
?>
<div class="table-responsive mb-3 table-thead-fixed" style="max-height: 700px; ">
<table class="table table-bordered table-sm" id='table3'>
    <thead>
        <tr class='text-center'>
            <th><div>№</div></th>
            <th><div>Дата</div></th>
            <th><div>Материально ответственное лицо</div></th>
            <th><div>Запчасть</div></th>
            <th><div>Склад</div></th>
			<th><div>Дата перемещения</div></th>
            <th><div>Количество</div></th>
            <th><div>Операция</div></th>
            <th><div>Оборудование</div></th>
        </tr>
    </thead>
    <tbody>
		<?php foreach ($writeOffs as $writeoff){ ?>
			<tr class='text-center'>
                <td><?php echo $writeoff->ID?></td>
                <td><?php echo d($writeoff->DATE)?></td>
                <td><?php echo $users[$writeoff->USER_ID]->fullname; ?></td>
                <td><?php echo $writeoff->NAME?></td>
                <td><?php echo $writeoff->STORE?></td>
				<td><?php echo d($writeoff->MOVINGDATE)?></td>
                <td><?php echo $writeoff->QUANTITY."&nbsp;".$writeoff->UNIT ?></td>
                <td><?php echo $operations[$writeoff->OPERATION_ID]->NAME ?></td>
                <td><?php echo $equipments[$writeoff->EQUIPMENT_ID]->path(); ?></td>
			</tr>
		<?php }?>
    </tbody>
</table>
</div>

<div class="modal fade" tabindex="-1" id='add-writeoff'>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class='modal-body'>
            <form action="instant_writeoff.php" target="_blank" method="get">
            <?php foreach($services as $service) { ?>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="add-operation-group-service-<?php echo $service->ID; ?>" name="service" value="<?php echo $service->ID; ?>">
                    <label class="custom-control-label" for="add-operation-group-service-<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></label>
                </div>
		    <?php } ?>
            <div class="mt-4"><button type="submit" class="btn btn-primary">Добавить списание ТМЦ</button></div>
            </form>
			</div>	
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" id='print-akt'>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="<?php echo $this->printUrl(); ?>" target="_blank">
            <input type="hidden" name="source" value="<?php echo Operation::SOURCE_GROUP_INDEX; ?>">
            <div class='modal-body text-center'>
                <div class="mb-4 h4">Укажите отчетный период:</div>
                <div class="mb-4">
                    <input type="date" name="date_from" value="<?php echo $this->filter['PLANNED_DATE_FROM']?>" class="form-control w-auto d-inline">
                    -
                    <input type="date" name="date_to" value="<?php echo $this->filter['PLANNED_DATE_TO']?>" class="form-control w-auto d-inline">
                </div>
                <button type="submit" class="btn btn-primary" onclick="openAktGo();">Напечатать</button>
			</div>	
		</div>
            </form>
	</div>
</div>

<script>
function openAkt()
{
    $('#print-akt').modal('show');
    $("#print-akt").appendTo("body")
}

function openAktGo()
{
    let url = '<?php echo $this->printUrl(); ?>&filter[PLANNED_DATE_FROM]=' + $('#print-akt').find('input[name="date_from"]').val() + '&filter[PLANNED_DATE_TO]=' + $('#print-akt').find('input[name="date_to"]').val();
    window.open(url);
}
</script>