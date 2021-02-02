<h3 class='mb-5' class='text-center'>Выберите службу</h3>

<form  method="post" action="">
<input type="hidden" name="save" value="1">
<input type="hidden" name="log_receiving_id" value="<?php echo $this->receiving->ID; ?>">
<div class="mb-3 row">
    <div class='col-2'>Служба</div>
    <div class="col-10">
        <?php foreach($services as $service) { ?>
            <div class="custom-control custom-checkbox">
            <input name="SERVICE_ID[]" value="<?php echo $service->ID; ?>" type="checkbox" class="custom-control-input" id="ch<?php echo $service->ID; ?>">
            <label class="custom-control-label" for="ch<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></label>
            </div>
        <?php } ?>
    </div>
</div>

<div class="mb-3 row">
    <div class='col-2'>Оборудование</div>
    <div class="col-10">
        <?php echo Equipment::find($this->receiving->EQUIPMENT_ID)->path(); ?>
    </div>
</div>
<div class="mb-3 row">
    <div class='col-2'>Дата и время комментария</div>
    <div class="col-10">
        <?php echo date("d.m.Y H:i:s", strtotime($this->receiving->created_at)); ?>
    </div>
</div>
<div class="mb-3 row">
    <div class='col-2'>Комментарий и фото</div>
    <div class="col-10">
        <?php   echo $this->receiving->COMMENT; ?>
        <div>
                    <?php foreach(json_decode($this->receiving->files ?? "[]") as $fileId) { 
                        $file = File::find($fileId);
                        ?>
                        <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><img src="<?php echo FileService::getUrl($file); ?>" width=150 class="mb-3 mr-3"></a>
                    <?php } ?>
                </div>
    </div>
</div>
<div class="mb-3 row">
    <div class='col-2'>Ваш комменатрий</div>
    <div class="col-10">
        <textarea name="COMMENT"class="form-control"></textarea>
    </div>
</div>

<div class='text-center'>
    <input type="submit" class='btn btn-primary' value="Сохранить">
</div>


</form>


