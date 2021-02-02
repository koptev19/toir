<div class='mb-1 row'>
    <div class='col-1'><a href="#" onclick="$('.filter').toggle(); return false;">Фильтр</a></div>
    <div class='col-2' id="clean-filter"><a href="history.php?workshop=<?php echo $this->workshop->ID; ?>">Сбросить фильтр</a></div>
</div>
<form method="get" action="history.php" id='filterForm'>
    <div class='filter mt-4 mb-4 col-8' style="display:none;">

    <?php if(isset($filter['service_request_id'])) { ?>
        <div class="mb-3 row">
            <div class='col-3'>Заявка на ремонт</div>
            <div class="col-9">
                <input type="text" name="filter[service_request_id]" class="form-control" value='<?php echo $filter['service_request_id']?>' disabled>
            </div>
        </div>
    <?php } ?>
    
        <div class="mb-3 row">
            <div class='col-3'>Оборудование</div>
            <div class="col-9">
                <?php
                $this->view('components/select_equipment', [
                    'fieldName' => 'filter[EQUIPMENT_ID]',
                    'equipment' => $filter['EQUIPMENT_ID']
                ]);
                ?>
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Дата выполнения</div>
            <div class="col-4">
                <input type="date" name="filter[PLANNED_DATE_FROM]" value="<?php echo $filter['PLANNED_DATE_FROM']?>" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-1">
                до
            </div>
            <div class="col-4">
                <input type="date" name="filter[PLANNED_DATE_TO]" value="<?php echo $filter['PLANNED_DATE_TO']?>" class="form-control" onchange="this.form.submit()">
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Название регламентной операции</div>
            <div class="col-9">
                <input type="text" name="filter[%NAME]" class="form-control" value='<?php echo $filter['%NAME']?>' onchange="this.form.submit()">
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Периодичноcть</div>
            <div class="col-9">
                <input type="number" name="filter[PERIODICITY]" class="form-control" value='<?php echo $filter['PERIODICITY']?>' onchange="this.form.submit()">
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Служба</div>
            <div class="col-9">
                <select name="filter[SERVICE_ID]" class="form-control form-select" onchange="this.form.submit()">
                    <option value="">Не важно</option>
                    <?php foreach($services as $service) { ?>
                        <option value="<?php echo $service->ID; ?>" <?php if($service->ID == $filter['SERVICE_ID']) echo "selected"; ?>><?php echo $service->NAME; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Комментарий</div>
            <div class="col-9">
                <input type="text" name="filter[%COMMENT]" class="form-control" value='<?php echo $filter['%COMMENT']?>' onchange="this.form.submit()">
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Тип операции</div>
            <div class="col-9">
                <select name="filter[TYPE_OPERATION]" class="form-control form-select" onchange="this.form.submit()">
                    <option value="">Не важно</option>
                    <?php foreach(Operation::getTypes() as $typeId => $typeName) { ?>
                        <option value="<?php echo $typeId; ?>" <?php if($typeId == $filter['TYPE_OPERATION']) echo "selected"; ?>><?php echo $typeName; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Ответственный</div>
            <div class="col-9">
                <input type="text" name="filter[%OWNER]" class="form-control" value='<?php echo $filter['%OWNER']?>' onchange="this.form.submit()">
            </div>
        </div>

        <div class="mb-3 row">
            <div class='col-3'>Результат</div>
            <div class="col-9">
                <select name="filter[RESULT]" class="form-control form-select" onchange="this.form.submit()">
                    <option value="">Не важно</option>
                    <option value="Y" <?php if($filter['RESULT'] == 'Y') echo "selected"; ?>>Выполнено</option>
                    <option value="N" <?php if($filter['RESULT'] == 'N') echo "selected"; ?>>Не выполнено</option>
                </select>
            </div>
        </div>
    </div>
<input type="hidden" name="limit" value="<?php echo $limit; ?>" id='filterLimit'>
<input type="hidden" name="page" value="1" id='filterPage'>
</form>

<script>
$(document).ready(function() {
    $('#equipment').on('change', function() {
        if($(this).val() && $(this).val() != '<?php echo $filter['EQUIPMENT_ID'];?>') {
            this.form.submit();
        }
    });
})
</script>