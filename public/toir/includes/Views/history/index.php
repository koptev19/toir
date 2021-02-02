<h4 class='text-center'>
    Журнал учета работ: <?php echo $this->workshop->NAME;?>
</h4>

<?php
$this->view('history/filter', compact('limit', 'services', 'filter'));
?>

<div class="table-responsive mb-3 table-thead-fixed" style="max-height: 600px; ">
<table class="table table-bordered table-sm table-hover" id='table3'>
    <thead>
        <tr class='text-center'>
            <th><div>№</div></th>
            <th><div>Дата выполнения</div></th>
            <th><div>Наименование оборудования</div></th>
            <th><div>Название регламентной операции</div></th>
            <th><div>Периодичноcть</div></th>
            <th><div>Время выполнения</div></th>
			<th><div>Служба</div></th>
			<th><div>Рекомендации</div></th>
            <th><div>Примечание</div></th>
            <th><div>Тип операции</div></th>
            <th><div>Ответственный исполнитель</div></th>
            <th><div>Результат</div></th>
            <th><div>Дата записи</div></th>
            <th><div>Документ</div></th>
            <th><div>Автор записи</div></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operations as $operation) {  
		?>
        <tr id='operation-<?php echo $operation->id; ?>'>
            <td><?php echo $operation->id; ?></td>
            <td class='text-center'><?php echo d($operation->PLANNED_DATE); ?></td>
            <td>
                <?php echo $equipments[$operation->EQUIPMENT_ID]->path(); ?>
            </td>
            <td><?php echo $operation->NAME; ?></td>
            <td class='text-center'><?php echo $operation->PERIODICITY; ?></td>
            <td class='text-center'><?php echo $operation->WORK_TIME; ?></td>
			<td ><?php echo $operation->service()->NAME; ?></td>
            <td><?php echo $operation->RECOMMENDATION; ?></td>
            <td><?php echo $operation->COMMENT; ?></td>
            <td class='text-center'><?php echo Operation::getVerbalType($operation->TYPE_OPERATION); ?></td>
            <td><?php echo $operation->OWNER; ?></td>
            <td class='text-center'>
                <?php if($operation->RESULT == 'N') { ?>
                    <div class='text-danger'><?php echo $operation->COMMENT_NO_RESULT; ?></div>
                <?php } elseif($operation->RESULT == 'Y') { ?>
                    <div class='text-success'>Выполнено</div>
                    <div><?php echo $operation->COMMENT_NO_RESULT; ?></div>
                <?php } ?>
            </td>
            <td class='text-center'><?php echo date("d.m.Y", strtotime($operation->created_at)); ?></td>
            <td class='text-center'><?php echo $operation->sourceAndLink(); ?></td>
            <td class='text-center'><?php echo $operation->author->fullname ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>
<?php
$this->view('components/paginate', [
    'maxPage' => $maxPage,
])
?>

<script>
var historyWorkshop = <?php echo $this->workshop->ID; ?>;
</script>