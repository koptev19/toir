<h3 class='text-center'>
    <?php echo $title; ?>
    <?php if(isset($canPrint) && $canPrint) { ?>
        <a href="#" onclick="printTable3('<?php echo $date; ?>', '<?php echo $this->workshop->ID; ?>'); return false;" class='ml-2' id='table3Print'><img src='./images/print.svg'></a>
    <?php } ?>
</h3>

<table class="table table-bordered table-sm" id='table3'>
    <thead>
        <tr class='text-center'>
            <th>№</th>
            <th>Наименование оборудования</th>
            <th>Название регламентной операции</th>
            <th>Служба</th>
            <th>ВИД ТО</th>
            <th>Время проведения</th>
            <th>Примечание</th>
            <th>Тип операции</th>
            <th>Периодичность в днях</th>
            <th>Ответственный исполнитель</th>
            <th>Планируемая Дата выполнения</th>
            <th>Дней до срока выполнения</th>
            <th>Результат</th>
            <th>Последняя дата выполнения</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operations as $operation) {  
        $plan = $operation->plan();
        ?>
        <tr id='operation-<?php echo $operation->ID; ?>'>
            <td><?php echo $operation->ID; ?></td>
            <td><?php echo $operation->equipment()->path(); ?></td>
            <td><?php echo $operation->NAME; ?></td>
            <td><?php echo $operation->service()->NAME; ?> (<?php echo $operation->PLAN_ID ? "Плановая" : "Внеплановая"; ?>)</td>
            <td><?php echo $plan->TYPE_TO; ?></td>
            <td><?php echo $operation->WORK_TIME; ?></td>
            <td><?php echo $operation->COMMENT; ?></td>
            <td><?php echo $operation->TYPE_OPERATION; ?></td>
            <td class='text-center'><?php echo $plan->PERIODICITY; ?></td>
            <td class='text-center'><?php echo $operation->OWNER; ?></td>
            <td class='text-center'><?php echo $operation->PLANNED_DATE; ?></td>
            <td class='text-center'><?php echo $operation->difference; ?></td>
            <td class='text-center'>
                <?php if($operation->TASK_RESULT == 'N') { ?>
                        <?php echo $operation->COMMENT_NO_RESULT; ?>
                <?php } elseif($operation->TASK_RESULT == 'Y') { ?>
                    <div class='text-success'>Выполнено</div>
                <?php } ?>
            </td>
            <td class='text-center'><?php echo $operation->LAST_DATE_FROM_CHECKLIST; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
