<h2 class='text-center mb-5'>Проверка планирования на <?php echo d($this->date); ?></h2>

<?php foreach($workshops as $workshop) { ?>
    <h3 class='text-center mb-3'><?php echo $workshop->NAME; ?></h3>
    <div class="table-responsive mb-5">
        <table class="table table-bordered table-hover">
            <thead>
                <tr class="text-center">
                    <th rowspan="2"></th>
                    <?php foreach($services as $service) { ?>
                        <th colspan="2" class="h5 align-top" width="<?php echo round(70 / count($services)) ?>%"><?php echo $service->NAME; ?>
						<?php 
						$dateProcess = DateProcessService::getByServiceAndDate($service, $workshop, $this->date);
						if($dateProcess && $dateProcess->COMMENT_EXPIRED) {?>
							<p class="text-danger" style="font-size:12px">Причина просрочки: <?php echo $dateProcess->COMMENT_EXPIRED; ?></p>
						<?php } ?>
						</th>
                    <?php } ?>
                    <th rowspan="2" class="align-middle">Время простоя</th>
                </tr>
                <tr class="text-center">
                    <?php foreach($services as $service) { 
                        $dateProcess = DateProcessService::getByServiceAndDate($service, $workshop, $this->date);
                        if($dateProcess) { 
                            $idCollapse = 'reject-' . $workshop->ID . '-' . $dateProcess->ID;
                            if($dateProcess->STAGE == DateProcess::STAGE_PLAN_DONE) { ?>
                                <th class="align-middle">
                                    <a href="?date=<?php echo $this->date; ?>&approve=<?php echo $dateProcess->ID; ?>" class="btn btn-primary">Утверждаю</a>
                                </th>
                                <th class="align-middle">
                                    <a href=".<?php echo $idCollapse; ?>" class="btn btn-outline-secondary <?php echo $idCollapse; ?> collapse show" data-bs-toggle="collapse" data-toggle="collapse" role="button" aria-expanded="false" id="<?php echo $idCollapse; ?>-1">Доработать</a>
                                    <div class="collapse pt-3 <?php echo $idCollapse; ?>" id="<?php echo $idCollapse; ?>-2">
                                        Комментарий:
                                        <form action="" method="POST">
                                            <input type="hidden" name="date" value="<?php echo $this->date; ?>">
                                            <input type="hidden" name="reject" value="<?php echo $dateProcess->ID; ?>">
                                            <textarea name="COMMENT" class="form-control mb-2 is-invalid mx-auto" required style="width:400px;"></textarea>
                                            <input type="submit" value="Отправить на доработку" class="btn btn-primary mr-5">
                                            <a href=".<?php echo $idCollapse; ?>" class="btn btn-outline-secondary ml-5" data-bs-toggle="collapse" data-toggle="collapse" role="button" aria-expanded="true">Отмена</a>
                                        </form>
                                    </div>
                                </th>
                            <?php } else { ?>
                                <td colspan="2" class="align-middle">
                                    <?php if($dateProcess->STAGE == DateProcess::STAGE_NEW) { ?>
                                        <span class="ml-4 font-italic">Планирование еще не производилось</span></a>
                                    <?php } ?>
                                    <?php if($dateProcess->STAGE == DateProcess::STAGE_PLAN_REJECTED) { ?>
                                        <span class="ml-4 text-danger">Отправлено на доработку</span> <a href="?date=<?php echo $this->date; ?>&cancel_stage=<?php echo $dateProcess->ID; ?>" class="ml-3"><img src="images/x.svg"></a>
                                        <div class="font-italic mt-3"><?php echo $dateProcess->COMMENT_REJECT; ?></div>
                                    <?php } ?>
                                    <?php if($dateProcess->STAGE == DateProcess::STAGE_PLAN_APPROVED) { ?>
                                        <span class="ml-4 text-success">Утверждено</span> <a href="?date=<?php echo $this->date; ?>&cancel_stage=<?php echo $dateProcess->ID; ?>" class="ml-3"><img src="images/x.svg"></a>
                                    <?php } ?>
                                    <?php if($dateProcess->STAGE == DateProcess::STAGE_REPORT_DONE) { ?>
                                        <span class="ml-4 font-italic">Пройден отчет</span></a>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        <?php } else { ?>
                            <td colspan="2" class="align-middle font-italic">Операций нет</td>                            
                        <?php }?>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach($workshop->lines as $line) { 
                $timeDuration = $this->timeDurationInWorkshop($workshop, $line);
                ?>
                <tr class="text-center <?php if($timeDuration) { ?>table-warning<?php } ?>">
                    <td class="text-left">
                        <?php if($timeDuration) { ?>
                            <a href="#" onclick="$('.operation-<?php echo $line->ID; ?>').toggle(); return false;"><?php echo $line->NAME; ?></a>
                        <?php } else { ?>
                            <?php echo $line->NAME; ?>
                        <?php } ?>
                    </td>
                    <?php foreach($services as $service) { 
                        $dateProcess = DateProcessService::getByServiceAndDate($service, $workshop, $this->date);
                        ?>
                        <td class="font-weight-bold"><?php echo $dateProcess ? $this->timeBeginEnd($dateProcess, $line) : ''; ?></td>
                        <td><?php echo $dateProcess ? $this->timeDuration($dateProcess, $line) : ''; ?></td>
                    <?php } ?>
                    <td><?php echo $timeDuration ?></td>
                </tr>
                <?php foreach($operations[$line->ID] ?? [] as $operation) { ?>
                    <tr class="text-center operation-<?php echo $line->ID; ?>" style="display:none;">
                        <td class="text-left pl-5"><?php echo $operation->NAME . ' (' . $operation->equipment->link() . ')'; ?></td>
                        <?php foreach($services as $service) { ?>
                            <td><?php echo $operation->SERVICE_ID == $service->ID ? $operation->WORK_TIME : ''; ?></td>
                            <td><?php echo $operation->SERVICE_ID == $service->ID ? $this->operationDuration($operation->ID) : ''; ?></td>
                        <?php } ?>
                        <td></td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
            <tfoot>
                <tr class="text-center table-secondary">
                    <td class="text-left">Время простоя по цеху:</td>
                    <?php foreach($services as $service) { 
                        $dateProcess = DateProcessService::getByServiceAndDate($service, $workshop, $this->date);
                        ?>
                        <td><?php echo $dateProcess ? $this->timeBeginEnd($dateProcess, null) : ''; ?></td>
                        <td><?php echo $dateProcess ? $this->timeDuration($dateProcess, null) : ''; ?></td>
                    <?php } ?>
                    <td><?php echo $this->timeDurationInWorkshop($workshop); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
<?php } ?>