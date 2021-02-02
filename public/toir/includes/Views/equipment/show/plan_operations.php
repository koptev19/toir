<div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
	<div class="btn-group mr-4" role="group" aria-label="First group">
		<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#equipmentOperations" aria-expanded="false" aria-controls="equipmentOperations">
    		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
        		<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
    		</svg> 
    		Плановые операции
		</button>
		<a href="add_plan.php?workshop=<?php echo $node->WORKSHOP_ID; ?>&equipment=<?php echo $node->ID; ?>" class="btn btn-primary" target="_blank"><span class="h5 font-weight-bold">+</span></a>
	</div>
	<div class="btn-group mr-4" role="group" aria-label="Second group">
		<button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#equipmentWorks" aria-expanded="false" aria-controls="equipmentWorks">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
				<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"></path>
			</svg> 
			Работы без даты
		</button>
		<a href="add_work.php?workshop=<?php echo $node->WORKSHOP_ID; ?>&equipment=<?php echo $node->ID; ?>" class="btn btn-primary" target="_blank"><span class="h5 font-weight-bold">+</span></a>
	</div>
	<div class="btn-group" role="group" aria-label="Third group">
		<a href="history.php?workshop=<?php echo $node->WORKSHOP_ID; ?>&line=<?php echo $node->LINE_ID; ?>&filter[EQUIPMENT_ID]=<?php echo $node->ID; ?>" class="btn btn-primary" target="_blank">История работ</a>
	</div>
</div>

<div class="my-4 collapse" id="equipmentOperations">
<h5 class='text-center'>Плановые операции</h5>
	<div id='table2'>
	    <table class="table table-bordered table-sm">
            <thead>
                <tr class='text-center'>
                    <th>Наименование оборудования</th>
                    <th>Причина возникновения операции</th>
                    <th>Название регламентной операции (Рекомендации)</th>
                    <th>Результат</th>
                    <th>Последняя дата выполнения</th>
                    <th>ВИД ТО<br>периодичность</th>
                    <th>Следующая Дата выполнения
                    <br>Просрочка
                    <th colspan=2></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($planOperations as $operation) { ?>
				<tr id='operation-<?php echo $operation->ID; ?>' class='text-center'>
                <td class='text-left'><?php echo $operation->equipment()->path(); ?></td>
                    <td>
                        <?php echo Operation::verbalReason($operation->REASON); ?>
                        <?php if($operation->REASON == Operation::REASON_VIEW) { ?>
                            <?php echo $operation->service()->SHORT_NAME; ?>
                        <?php } ?>
                        <br>
                        (<?php echo $operation->TYPE_OPERATION; ?>)
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
                     <td>
                            <?php echo $operation->TYPE_TO; ?><br>
                            <?php echo $operation->PERIODICITY; ?> дн.
                     </td>
                      <td>
                        
                        <?php echo $operation->NEXT_EXECUTION_DATE; ?>
                        <?php if($operation->late > 0) {?>
                            <div class='text-danger'><?php echo $operation->late; ?> дн.</div>
                            <div>от <?php echo $operation->getLateDate(); ?></div>
                            
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
</div>

<div class="my-4 collapse" id="equipmentOperations">
<h5 class='text-center'>Работы без даты</h5>
	<div id='table2'>
	    <table class="table table-bordered table-sm">
            <thead>
                <tr class='text-center'>
                    <th>Наименование оборудования</th>
                    <th>Название регламентной операции (Рекомендации)</th>
                    <th>Служба</th>
                    <th>Последняя дата выполнения</th>
                    <th>ВИД ТО</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($works as $work) { ?>
				<tr id='work-<?php echo $work->ID; ?>' class='text-center'>
                    <td class='text-left'><?php echo $work->equipment->path(); ?></td>
                    <td class='text-left'>
                        <div class="font-weight-bold"><?php echo $work->NAME; ?></div>
                        <?php if($work->RECOMMENDATION) {?>
                            <div class="font-italic fst-italic text-info"><?php echo $work->RECOMMENDATION; ?></div>
                        <?php } ?>
                    </td>
                    <td><?php echo $work->service->SHORT_NAME; ?></td>
                    <td><?php echo $work->LAST_COMPLETED; ?></td>
                    <td><?php echo $work->TYPE_TO; ?></td>
                </tr>
            <?php } ?>
            </tbody>    
        </table>
    </div>
</div>
