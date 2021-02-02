<?php $this->view('index/table2_header'); ?>

<div id='table2' style="display:<?php echo($_COOKIE["table2" . $this->workshop->ID] == "show")?"block":"none";?>">
    <table class="table table-bordered table-sm">
        <thead>
            <tr class='text-center'>
                <th>Наименование оборудования</th>
                <th>Название регламентной операции (Рекомендации)</th>
                <th>Результат</th>
                <th>Последняя дата выполнения</th>
                <th>ВИД ТО<br>периодичность</th>
                <th>Планируемая дата выполнения<br>Просрочка</th>
                <th>Причина возникновения операции</th>
                <th>Служба</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($operations as $operation) { ?>
            <tr id='operation-<?php echo $operation->ID; ?>' class='text-center'>
                <td class='text-left'><?php echo $operation->equipment()->path(); ?></td>
                <td class='text-left'>
                    <div class="font-weight-bold"><?php echo $operation->NAME; ?></div>
                    <?php if($operation->RECOMMENDATION) {?>
                        <div class="font-italic fst-italic text-info"><?php echo $operation->RECOMMENDATION; ?></div>
                    <?php } ?>
                </td>
                <td>
                    <?php if($operation->status == 'N') { ?>
                        <div class='text-danger'><?php echo $operation->COMMENT_NO_RESULT; ?></div>
                    <?php } elseif($operation->status == 'Y') { ?>
                        <div class='text-success'>Выполнено</div>
                    <?php } ?>
                </td>
                <td><?php echo $operation->LAST_DATE_FROM_CHECKLIST; ?></td>
                <td>
                    <?php echo $operation->TYPE_TO; ?><br>
                    <?php echo $operation->PERIODICITY; ?> дн.
                </td>
                <td>
                    <a href="master_plan_date.php?mode=dates&selected=<?php echo $operation->ID ?>&date=<?php echo $operation->nextExecutionDate ?>&service=<?php echo $operation->SERVICE_ID ?>" target="_blank">
                        <?php echo $operation->nextExecutionDate; ?>
                    </a>
                    <?php if($operation->late > 0) {?>
                        <div class='text-danger'><?php echo $operation->late; ?> дн.</div>
                    <?php } else { ?>
                        <div class='text-success'>Нет</div>
                    <?php } ?>
                </td>
                <td>
                    <?php echo Operation::verbalReason($operation->REASON); ?>
                    <?php if($operation->REASON == Operation::REASON_VIEW) { ?>
                        <?php echo $operation->service()->SHORT_NAME; ?>
                    <?php } ?>
                    <br>
                    (<?php echo $operation->TYPE_OPERATION; ?>)
                </td>
                <td>
                    <?php echo $operation->service->NAME; ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>    
    </table>
</div>