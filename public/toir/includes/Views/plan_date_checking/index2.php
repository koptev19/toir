<h3 class='text-center mb-5'>Проверка планирования на <?php echo $this->date; ?></h3>

<?php foreach($dateProcesses as $dateProcess) { ?>
    <h5 class="mt-5 mb-3 text-center">
        <?php echo $dateProcess->service->NAME; ?>

        <?php if($dateProcess->STAGE == DateProcess::STAGE_PLAN_APPROVED) { ?>
            <span class="ml-4 text-success">Одобрено</span>
        <?php } ?>

        <?php if($dateProcess->STAGE == DateProcess::STAGE_PLAN_REJECTED) { ?>
            <span class="ml-4 text-danger">Отправлено на доработку</span>
        <?php } ?>
    </h5>
    <div class="table-responsive">
        <table class="table table-bordered table-sm table-hover">
            <thead>
                <tr class="text-center">
                    <th>№</th>
                    <th>Наименование оборудования</th>
                    <th>Название регламентной операции</th>
                    <th>Тип операции</th>
                    <th>Время выполнения</th>
                    <th>Примечание</th>
                    <?php foreach($workers[$dateProcess->ID] as $workerId => $workerName) { ?>
                        <th><?php echo $workerName; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach($workshops as $workshop) { ?>
                <tr>
                    <td colspan="100%" class="table-info p-2 text-center"><?php echo $workshop->NAME; ?></td>
                </tr>
                <?php if(count($groupedOperations[$dateProcess->ID][$workshop->ID]) > 0) { ?>
                    <?php foreach($groupedOperations[$dateProcess->ID][$workshop->ID] as $operation) { ?>
                        <tr class="text-center">
                            <td><?php echo $operation->ID; ?></td>
                            <td class="text-left"><?php echo $operation->equipment->path(); ?></td>
                            <td class="text-left"><?php echo $operation->NAME; ?></td>
                            <td><?php echo $operation->TYPE_OPERATION; ?></td>
                            <td><?php echo $operation->WORK_TIME; ?></td>
                            <td><?php echo $operation->COMMENT; ?></td>
                            <?php foreach($workers[$dateProcess->ID] as $workerId => $workerName) { ?>
                                <td><?php echo $times[$dateProcess->ID][$operation->ID][$workerId] ? implode(" - ", $times[$dateProcess->ID][$operation->ID][$workerId]) : ''; ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                <tr>
                    <td colspan="100%" class="p-2 text-center">Операций нет</td>
                </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if($dateProcess->STAGE == DateProcess::STAGE_PLAN_DONE) { ?>
        <div class="row pb-5">
            <div class="col-6">
                <a href="#reject-<?php echo $dateProcess->ID; ?>" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="#reject-<?php echo $dateProcess->ID; ?>">Доработать</a>
            </div>
            <div class="col-6 text-right">
                <a href="?date=<?php echo $this->date; ?>&approve=<?php echo $dateProcess->ID; ?>" class="btn btn-primary">Утверждаю</a>
            </div>
            <div class="col-12 pt-3 collapse" id="reject-<?php echo $dateProcess->ID; ?>">
                <form action="" method="POST">
                    <input type="hidden" name="date" value="<?php echo $this->date; ?>">
                    <input type="hidden" name="reject" value="<?php echo $dateProcess->ID; ?>">
                    <label class="form-label">Комментарий:</label>
                    <textarea name="COMMENT" class="form-control mb-2 is-invalid"></textarea>
                    <input type="submit" value="Отправить на доработку" class="btn btn-secondary">
                </form>
            </div>
        </div>
    <?php } ?>


<?php } ?>

