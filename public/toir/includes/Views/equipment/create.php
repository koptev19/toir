<form>
    <div class="error alert alert-danger" role="alert" style="display:none;"></div>
    <input type=hidden name="PARENT_ID" value="<?php echo $parent->ID; ?>">

    <div class="row mb-3">
        <div class='col-4 col-md-2'>Тип:</div>
        <div class="col-10">
        <? if (!$parent) { ?>
            <input type="hidden" name="TYPE" value="<?php echo Equipment::TYPE_WORKSHOP; ?>">
            <input type="text" value="<?php echo Equipment::$types[Equipment::TYPE_WORKSHOP]; ?>" class='form-control bg-light' readonly>
        <? } elseif ($parent->LEVEL == 1) { ?>
            <input type="hidden" name="TYPE" value="<?php echo Equipment::TYPE_LINE; ?>">
            <input type="text" value="<?php echo Equipment::$types[Equipment::TYPE_LINE]; ?>" class='form-control bg-light' readonly>
        <? } else { ?>
            <select class='form-control form-select' name="TYPE">
            <?php foreach(Equipment::$types as $key => $name) { 
                if($key == Equipment::TYPE_WORKSHOP || $key == Equipment::TYPE_LINE) continue;
                ?>
                <option value='<? echo $key; ?>'><?php echo $name; ?></option>
            <?php } ?>	
            </select>
        <? } ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class='col-4 col-md-2'>Наименование:</div>
        <div class="col-10"><input type="text" name="NAME" value="" class='form-control' required></div>
    </div>
    <?php
    if($level == 1) {
        $this->view('equipment/create/workshop');
    }
    ?>

    <div class='mt-4'>
        <a onClick="storeNode(this); return false;" class='btn btn-primary'>Сохранить</a>
        <a onClick="showNode(<?php echo $parent ? $parent->ID : 0; ?>); return false" class='btn btn-outline-secondary ml-5'>Отмена</a>
    </div>
</form>