<div class="mb-3 row">
    <label class='col-2 col-form-label font-weight-bold'>Оборудование</label>
    <label class='col-10 col-form-label font-weight-bold' id="equipment-name"><?php echo $equipment ? $equipment->path(false) : ''; ?></label>
    <input type="hidden" name="equipment" id="equipment-id" value="<?php echo $equipment ? $equipment->ID : ''; ?>">
</div>

<div class="mb-3 row">
    <label class='col-2 col-form-label'>Служба</label>
    <div class="col-10">
        <select name="SERVICE_ID" required class="custom-select form-select">
            <option value="" disabled selected hidden>Выберите</option>
            <?php foreach(UserToir::current()->availableServices as $service) { ?>
                <option value="<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Название операции</div>
    <div class="col-10">
        <input type="text" name="NAME" class="form-control" required>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Тип операции</div>
    <div class="col-10">
        <select name="TYPE" required class="custom-select form-select">
            <option value="" disabled selected hidden>Выберите</option>
            <?php foreach(Operation::getTypes() as $typeId => $typeName) { ?>
                <option value="<?php echo $typeId; ?>"><?php echo $typeName; ?></option>
            <?php } ?>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Рекомендации</div>
    <div class="col-10">
        <input type="text" name="RECOMMENDATION" class="form-control">
    </div>
</div>
