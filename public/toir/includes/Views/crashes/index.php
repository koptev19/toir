<?php $this->view('crashes/header'); ?>

<h3 class='text-center'>Журнал аварий</h3>

<div class='my-3'>
    <a href="crash_create.php?workshop=<?php echo $this->workshop->ID; ?>" target=_blank class='btn btn-primary'>Новая авария</a>
</div>

<div class="table-responsive mb-3 table-thead-fixed" style="max-height: 700px; ">
    <table class="table table-bordered table-sm" id='table3'>
        <thead>
            <tr class='text-center'>
                <th><div>№</div></th>
                <th><div>Дата / Время</div></th>
                <th><div>Оборудование</div></th>
                <th><div>Описание аварии и операции по её устранению</div></th>
                <th><div>Решение по предотвращению аварии</div></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($crashes as $crash) { ?>
            <tr style="height:70px;">
                <td class="text-center" width="50"><?php echo $crash->ID; ?></td>

                <td class="text-center" width="100">
                    <?php echo date("d.m.Y", strtotime($crash->DATE)); ?><br>
                    <?php echo $crash->TIME_FROM; ?> - <?php echo $crash->TIME_TO; ?>
                </td>

                <td width="100"><?php echo $crash->equipment()->path(); ?></td>

                <td rowspan="2" <?php if($crash->STATUS == Crash::STATUS_NEW || $crash->STATUS == Crash::STATUS_DESCRIPTION) {?> class="border-3 border-danger" style="border-width:4px;"<?php } ?>>
                    <div class="mb-3">
                        <?php if($crash->STATUS < Crash::STATUS_DONE) { ?>
                            <?php if($crash->DESCRIPTION) { ?>
                                <a href="#" onclick="editDescription(<?php echo $crash->ID; ?>); return false;" class='mr-2 float-left'><img src='./images/pencil.svg'></a>
                                <?php echo $crash->DESCRIPTION; ?>
                            <?php } else { ?>
                                <a href="#" onclick="editDescription(<?php echo $crash->ID; ?>); return false;">Добавить описание</a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php if ($crash->serviceRequests) { ?>
                        <?php if ($crash->STATUS >= Crash::STATUS_OPERATIONS) { ?>
                            <div class="mb-3">
                                <a href="#" onclick="showHistories(<?php echo $crash->ID; ?>); return false;">Статус операций</a>
                            </div>
                        <?php } ?>
                        <?php if($crash->STATUS >= Crash::STATUS_SERVICE_REQUEST) { ?>
                            <div class="mb-3">
                                <table class="table table-sm table-bordered">
                                    <tr>
                                        <td colspan=2 class="table-info text-center fw-bold">Выполненные операции</td>
                                    </tr>
                                    <?php $num = 0; 
                                    foreach($crash->historiesByService as $historiesArray) { 
                                        $cellSercive = '<td rowspan="' . count($historiesArray['operations']) .'">' . $historiesArray['name'] . '</td>';
                                        foreach($historiesArray['operations'] as $operation) { 
                                            $num++; 
                                            ?>
                                            <tr>
                                                <td><?php echo $num . '. ' . $operation->NAME; ?></td>
                                                <?php echo $cellSercive; ?>
                                            </tr>
                                            <?php 
                                            $cellSercive = '';
                                        }?>
                                    <?php } ?>
                                </table>
                            </div>
                            <?php if($crash->STATUS >= Crash::STATUS_SERVICE_REQUEST && $crash->STATUS < Crash::STATUS_DONE) { ?>
                                <div class="mb-3">
                                    <a href="#" onclick="addHistory(<?php echo $crash->ID; ?>); return false;">Добавить операцию</a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    <?php } elseif($crash->STATUS >= Crash::STATUS_DESCRIPTION) { ?>
                        <div class="mb-3">
                            <a href="crash_request.php?crash_id=<?php echo $crash->ID?>" target="_blank">Привлечь службу</a>
                        </div>
                    <?php } ?>
                    <?php if($crash->STATUS < Crash::STATUS_DONE && $crash->STATUS > Crash::STATUS_NEW) { ?>
                        <div class="mb-3">
                            <a href="#" onclick="addFiles(<?php echo $crash->ID; ?>); return false;">Добавить файлы</a>
                        </div>
                    <?php } ?>
                    <?php foreach(json_decode($crash->DOCUMENTS ?? "[]") as $fileId) { 
                        $file = File::find($fileId); 
                        if(!$file) {
                            continue;
                        }
                        ?>
                        <div id='file-<?php echo $fileId; ?>' class="mb-3">
                            <div style="height:1px;">
                                <a href="#" onclick="delDocumentFile(<?php echo $crash->ID; ?>, <?php echo $fileId; ?>); return false;" style="position:relative; z-index:10; margin-left:220px; margin-top:25px;"><img src='images/x.svg'></a>
                            </div>
                            <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><img src="<?php echo FileService::getUrl($file); ?>" class="m-3" style="max-width:200px;"></a>
                        </div>
                    <?php } ?>
                </td>

                <td rowspan="2" <?php if($crash->STATUS == Crash::STATUS_OPERATIONS || $crash->STATUS == Crash::STATUS_DECISION) {?> class="border-3 border-danger" style="border-width:4px;"<?php } ?>>
                    <div class="mb-3">
                        <?php if($crash->STATUS < Crash::STATUS_DONE) { ?>
                            <?php if($crash->DECISION) { ?>
                                <a href="#" onclick="editDecision(<?php echo $crash->ID; ?>); return false;" class='mr-2 float-left'><img src='./images/pencil.svg'></a>
                                <?php echo $crash->DECISION; ?>
                            <?php } else { ?>
                                <a href="#" onclick="editDecision(<?php echo $crash->ID; ?>); return false;">Добавить решение</a>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <?php if (count($crash->operationsByService)) { ?>
                        <div class="mb-3">
                            <a href="#" onclick="showOperations(<?php echo $crash->ID; ?>); return false;">Статус операций</a>
                        </div>
                    <?php } ?>
                    <?php if($crash->STATUS >= Crash::STATUS_DECISION) { ?>
                        <?php if(count($crash->operationsByService)) { ?>
                            <div class="mb-3">
                                <table class="table table-sm table-bordered">
                                    <tr>
                                        <td colspan=2 class="table-info text-center fw-bold">Добавленные операции</td>
                                    </tr>
                                    <?php $num = 0; 
                                    foreach($crash->operationsByService as $operationsArray) { 
                                        $cellSercive = '<td rowspan="' . count($operationsArray['operations']) .'">' . $operationsArray['name'] . '</td>';
                                        foreach($operationsArray['operations'] as $operation) { 
                                            $num++; 
                                            ?>
                                            <tr>
                                                <td><?php echo $num . '. ' . $operation->NAME; ?></td>
                                                <?php echo $cellSercive; ?>
                                            </tr>
                                            <?php 
                                            $cellSercive = '';
                                        }?>
                                    <?php } ?>
                                </table>
                            </div>
                        <?php } ?>
                        <?php if($crash->STATUS < Crash::STATUS_DONE) { ?>
                            <div class="mb-3">
                                <a href="add_plan.php?workshop=<?php echo $crash->WORKSHOP_ID; ?>&equipment=<?php echo $crash->EQUIPMENT_ID; ?>&crash=<?php echo $crash->ID; ?>" target=_blank>Добавить плановую операцию</a>
                            </div>
                            <div class="mb-3">
                                <a href="#" onclick="addOperationGroup('add_operation_group.php', <?php echo $crash->ID; ?>); return false;">Добавить внеплановые операции</a>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php if($crash->STATUS < Crash::STATUS_DONE && $crash->STATUS >= Crash::STATUS_DECISION) { ?>
                        <div class="mb-3">
                            <a href="#" onclick="addDecisionFiles(<?php echo $crash->ID; ?>); return false;">Добавить файлы</a>
                        </div>
                    <?php } ?>
                    <?php foreach(json_decode($crash->DECISION_DOCUMENTS ?? "[]") as $fileId) {
                        $file = File::find($fileId); 
                        if(!$file) {
                            continue;
                        }
                        ?>
                        <div id='file-<?php echo $fileId; ?>' class="mb-3">
                            <div style="height:1px;">
                                <a href="#" onclick="delDecisionFile(<?php echo $crash->ID; ?>, <?php echo $fileId; ?>); return false;" style="position:relative; z-index:10; margin-left:220px; margin-top:25px;"><img src='images/x.svg'></a>
                            </div>
                            <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><img src="<?php echo FileService::getUrl($file); ?>" class="m-3" style="max-width:200px;"></a>
                        </div>
                    <?php } ?>
                </td>
            </tr>

            <tr>
                <td colspan="3" class="p-4 text-center">
                    <div class="<?php if($crash->STATUS != Crash::STATUS_DONE) {?>text-danger font-weight-bold<?php } ?>"><?php echo Crash::verbalStatus($crash->STATUS); ?></div>
                    <?php if($crash->STATUS == Crash::STATUS_DECISION) { ?>
                        <br><br>
                        <a href="crash_edit.php?crash=<?php echo $crash->ID; ?>&done=1">Завершить</a>
                    <?php } ?>
                </td>
            </tr>
            <tr><td colspan="100%" class="table-secondary">&nbsp;</td></tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<?php
$this->view('crashes/modals');
$this->view('_footer');
?>