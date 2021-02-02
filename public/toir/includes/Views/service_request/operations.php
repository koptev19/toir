<table class='table table-bordered table-hover'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Дата</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operations as $operation) { ?>
        <tr>
            <td><?php echo $operation->ID; ?></td>
            <td>
                <?php if(!is_a($operation, History::class)) { ?>
                    <a href="get_table3.php?date=<?php echo $operation->PLANNED_DATE; ?>&workshop=<?php echo $operation->WORKSHOP_ID; ?>&header=1" target=_blank><?php echo $operation->NAME; ?></a>
                <?php } else { ?>
                    <?php echo $operation->NAME; ?>
                <?php } ?>
            </td>
            <td><?php echo $operation->PLANNED_DATE; ?></td>
            <td>
                <?php if($operation->TASK_RESULT == 'Y') { ?>
                    <div class='text-success'><a href="history.php?workshop=<?php echo $operation->WORKSHOP_ID; ?>&filter[service_request_id]=<?php echo $serviceRequest->ID; ?>" target=_blank>Выполнено</a></div>
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