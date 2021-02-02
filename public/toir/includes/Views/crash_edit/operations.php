<table class="table table-bordered table-sm">
    <thead>
        <tr class='text-center'>
            <th>№</th>
            <th>Название</th>
            <th>Дата</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operations as $operation) {
        $historyDate = is_a($operation, Plan::class) ? $operation->START_DATE : $operation->PLANNED_DATE;
		?>
        <tr>
            <td class="text-center"><?php echo $operation->ID; ?></td>
            <td class="text-center">
                <?php if(is_a($operation, Plan::class) || !$operation->TASK_RESULT) { ?>
                    <a href="index.php?workshop=<?php echo $operation->WORKSHOP_ID; ?>&filter_name=<?php echo $operation->NAME;?>&table2=<?php echo is_a($operation, Plan::class) ? 'plan' : 'noplan'; ?>" target=_blank>
                        <?php echo $operation->NAME; ?>
                    </a>
                <?php } else { ?>
                    <?php echo $operation->NAME; ?>
                <?php } ?>
            </td>
			<td class="text-center"><?php echo $operation->date; ?></td>
			<td class="text-center">
                <?php if($operation->TASK_RESULT == 'Y') { ?>
                    <div class='text-success'>
                        <a href="history.php?workshop=<?php echo $operation->WORKSHOP_ID; ?>&equipment=<?php echo $operation->EQUIPMENT_ID; ?>&filter[PLANNED_DATE_FROM]=<?php echo $historyDate; ?>&filter[PLANNED_DATE_TO]=<?php echo $historyDate; ?>" target=_blank>Выполнено</a>
                    </div>
                <?php } elseif($operation->TASK_RESULT == 'N') { ?>
                    <div class='text-danger'>Не выполнено</div>
                <?php } else { ?>
                    <div>В работе</div>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
