<h3 class='text-center'>
    Журнал списания ТМЦ 
    <button type="button" class='ml-3 btn btn-outline-primary' data-bs-toggle="modal" data-bs-target="#print-akt">Акт списания ТМЦ</button>
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
                <td><?php echo $writeoff['ID']?></td>
                <td><?php echo $writeoff['UF_DATE']->format("d.m.Y")?></td>
                <td><?php echo $users[$writeoff['UF_USERID']]; ?></td>
                <td><?php echo $writeoff['UF_NAME']?></td>
                <td><?php echo $writeoff['UF_STORE']?></td>
				<td><?php echo $writeoff['UF_MOVINGDATE']?></td>
                <td><?php echo $writeoff['UF_QUANTITY']."&nbsp;".$writeoff['UF_UNIT'] ?></td>
                <td><?php echo $operations[$writeoff['UF_OPERATIONID']]->NAME ?></td>
                <td><?php echo $equipments[$writeoff['UF_EQUIPMENTID']]->path(); ?></td>
			</tr>
		<?php }?>
    </tbody>
</table>
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