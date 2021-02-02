<?php $this->view('index/table2_header'); ?>

    <div id='table2' style="display:<?php echo($_COOKIE["table2" . $this->workshop->ID] == "show")?"block":"none";?>">
  
	    <table class="table table-bordered table-sm">
            <thead>
                <tr class='text-center'>
                    <th>Наименование оборудования</th>
                    <th>Причина возникновения операции (Тип операции)</th>
                    <th>Название регламентной операции (Рекомендации)</th>
                    <th>Результат</th>
                    <th>Последняя дата выполнения</th>
                <?php if ($this->table2 == 'plan'){ ?>
                        <th>ВИД ТО<br>периодичность</th>
                    <?php } ?>
                    <th>
                <?php if ($this->table2 == 'plan'){ ?>
                        Следующая дата выполнения
                    <?php } else { ?>
                        Планируемая дата выполнения
                    <?php } ?>
                        <br>(Просрочка)
                    <th colspan=2></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($operations as $operation) { ?>
				<tr id='operation-<?php echo $operation->ID; ?>' class='text-center'>
                <td class='text-left'><?php echo $operation->equipment()->path(); ?></td>
                    <td>
                        <?php echo Operation::verbalReason($operation->REASON); ?>
                        <?php if($operation->REASON == Operation::REASON_VIEW) { ?>
                            <?php echo $operation->service()->SHORT_NAME; ?>
                        <?php } ?>
                        <br>
                        (<?php echo Operation::getVerbalType($operation->TYPE_OPERATION); ?>)
                    </td>
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
                <?php if ($this->table2 == 'plan'){ ?>
                        <td>
                            <?php echo $operation->TYPE_TO; ?><br>
                            <?php echo $operation->PERIODICITY; ?> дн.
                        </td>
                    <?php } ?>
                    <td>
                    <?php if ($this->table2 == 'plan'){ ?>
                        <a href="master_plan_date.php?mode=dates&selected=<?php echo $operation->ID; ?>&date=<?php echo $operation->NEXT_EXECUTION_DATE; ?>&service=<?php echo $operation->SERVICE_ID; ?>" target="_blank"><?php echo $operation->NEXT_EXECUTION_DATE; ?></a>
                        <?php } else { ?>
                        <a href="master_plan_date.php?mode=dates&selected=<?php echo $operation->ID; ?>&date=<?php echo $operation->PLANNED_DATE; ?>&service=<?php echo $operation->SERVICE_ID; ?>" target="_blank"><?php echo date("d.m.Y", strtotime($operation->PLANNED_DATE)); ?></a>
                        <?php } ?>
                        <?php if($operation->late > 0) {?>
                            <div class='text-danger'><?php echo $operation->late; ?> дн.</div>
                        <?php if($this->table2 == 'plan') { ?>
                                <div>от <?php echo $operation->getLateDate(); ?></div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class='text-success'>Нет</div>
                        <?php } ?>
                    </td>
                    <td class='text-nowrap'>
                        <div class='links'>
                            <a href="edit_operation.php?operation_id=<?php echo $operation->ID; ?>" target=_blank><img src="./images/pencil.svg" /></a>
                            <a href="del_operation.php?workshop=<?php echo $this->workshop->ID; ?>&year=<?php echo $this->year; ?>&month=<?php echo $this->month; ?>&id=<?php echo $operation->ID; ?>" onclick="return confirm('Удалить?')" class='ml-3'><img src="./images/x.svg" /></a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>    
        </table>
    </div>

