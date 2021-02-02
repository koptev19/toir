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
