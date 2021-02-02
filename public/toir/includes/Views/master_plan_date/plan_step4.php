<?php
$this->view('components/select_equipment', ["multiply"=>true]);
?>

<div class="table-responsive mb-3 table-thead-fixed">
<table class="table table-bordered table-sm table-hover">
        <thead>
            <tr class='text-center'>
                <th><div>Наименование оборудования</div></th>
                <th><div>Название регламентной операции</div></th>
                <th><div>Тип операции</div></th>
                <th><div>Примечание</div></th>
                <th><div>Выполнить</div></th>
                <th><div>Причина невыполнения</div></th>
                <th><div>Следующая дата выполнения</div></th>
                <?php foreach($cookie['workersNames'] as $key => $worker) {
                    if($worker) {
                    ?>
                    <th><div><?php echo $worker; ?></div></th>
                <?php } } ?>
                <th><div></div></th>
            </tr>
        </thead>
        <tbody>
        <?php 
        //dump($cookie);
        $cookie['result'] = json_decode($cookie['result'], true);
        foreach($operationsInLine as $lineName => $operations) { ?>
            <tr class='line-name'>
                <td class='table-warning text-center' colspan=100%><?php echo $lineName; ?></td>
            </tr>
            <?php foreach($operations as $operation) { 
                $operationDate = is_array($cookie['done']) && in_array($operation->ID, $cookie['done']) ? '': d($cookie['day'][$operation->ID] . '.' . $cookie['month'][$operation->ID] . '.' . $cookie['year'][$operation->ID]);
                ?>
            <tr class='text-center align-middle'>
                <td class='text-left pb-0' width="200">
                    <?php if(is_a($operation, Operation::class)) { ?>
                        <?php echo $operation->equipment ? $operation->equipment->path(false) : ''; ?>
                    <?php } else { ?>
                        <form action="" method="POST" class="m-0 p-0">
                            <input type="hidden" name="operation" value="<?php echo $operation->ID; ?>">
                            <input type="hidden" name="update_field" value="EQUIPMENT_ID">
                            <table with=100% class="table table-sm table-borderless"><tr><td>
                                <input type=hidden name='value' class='equipment-select-input' value="<?php echo $operation->EQUIPMENT_ID; ?>" id="operation-equipment-<?php echo $operation->EQUIPMENT_ID; ?>">
                            </td>                            
                            <td>
                                <button type="submit" class="btn border-0 p-1"><img src="images/check.svg"></button>
                            </td></tr></table>
                        </form>
                    <?php } ?>
                </td>
                <td class='text-left' width="200">
                    <?php if(!is_a($operation, Operation::class)) { ?>
                        <form action="" method="POST">
                            <input type="hidden" name="operation" value="<?php echo $operation->ID; ?>">
                            <input type="hidden" name="update_field" value="NAME">
                            <table with=100% class="table table-sm table-borderless"><tr><td>
                                <input type="text" name="value" value="<?php echo $operation->NAME; ?>" class="form-control border-0" onfocus="$(this).parent().parent().find('button').removeClass('invisible')">
                            </td>                            
                            <td>
                                <button type="submit" class="btn border-0 invisible"><img src="images/check.svg"></button>
                            </td></tr></table>
                        </form>
                    <?php } else { ?>
                        <?php echo $operation->NAME; ?>
                    <?php } ?>
                </td>
                <td>
                    <span class="btn" onclick="$('#form-type-operation-<?php echo $operation->ID; ?>').show(); $(this).hide();"><?php echo $typesOperation[$cookie['TYPE_OPERATION'][$operation->ID]]; ?></span>
                    <form action="" method="POST" id="form-type-operation-<?php echo $operation->ID; ?>" style="display:none;">
                        <input type="hidden" name="operation" value="<?php echo $operation->ID; ?>">
                        <input type="hidden" name="update_field" value="TYPE_OPERATION">
                        <table with=100% class="table table-sm table-borderless"><tr><td>
                            <select name="value" class="form-control form-select" onfocus="$(this).parent().parent().find('button').removeClass('invisible')">
                                <?php foreach($typesOperation as $typeId => $typeName) { ?>
                                    <option value="<?php echo $typeId; ?>" <?php if($cookie['TYPE_OPERATION'][$operation->ID] == $typeId) echo "selected"; ?>><?php echo $typeName; ?></option>
                                <?php } ?>
                            </select>
                        </td>                            
                        <td>
                            <button type="submit" class="float-right btn border-0 invisible"><img src="images/check.svg"></button>
                        </td></tr></table>
                    </form>
                </td>
                <td width="300">
                    <form action="" method="POST">
                        <input type="hidden" name="operation" value="<?php echo $operation->ID; ?>">
                        <input type="hidden" name="update_field" value="COMMENT">
                        <table with=100% class="table table-sm table-borderless"><tr><td>
                            <input type="text" name="value" value="<?php echo $cookie['COMMENT'][$operation->ID]; ?>" class="form-control border-0" onfocus="$(this).parent().parent().find('button').show()">
                        </td>                            
                        <td>
                            <button type="submit" class="float-right btn border-0" style="display:none;"><img src="images/check.svg"></button>
                        </td></tr></table>
                    </form>
                </td>
                <td><?php echo is_array($cookie['done']) && in_array($operation->ID, $cookie['done']) ? "Да" : "Нет"; ?></td>
                <td><?php echo $cookie['COMMENT_NO_RESULT'][$operation->ID]; ?></td>
                <td><?php echo $operationDate; ?></td>
                <?php foreach($cookie['workers'] as $key => $worker) {
                    if(!$worker)  continue;
                    ?>
                    <td><?php echo $cookie['result'][$key][$operation->ID] ? implode(' - ', $cookie['result'][$key][$operation->ID]) : ''; ?></td>
                <?php } ?>
                <td>
                <?php if(!is_a($operation, Operation::class)) { ?>
                    <a href="?delete_in_session=<?php echo $operation->ID; ?>&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>"><img src='images/x.svg'></a>
                <?php } ?>
                </td>
            </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>

<div class='mt-5 text-center'>
    
    
</div>

<div class="row">
    <div class="col-6 pr-5 text-right">
        <a href="?mode=plan&step=4&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>&save=1" onClick="submitForm()" class="btn btn-warning table-warning" style="background-color: #ffeeba; ">Согласовать</a>
    </div>
    <div class="col-6 pl-5">
        <a href="?mode=plan&step=3&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>" class='btn btn-outline-secondary mr-5'>Есть ошибки. Вернуться на шаг назад</a>
    </div>
</div>
