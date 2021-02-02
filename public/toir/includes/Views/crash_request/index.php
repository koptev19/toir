<h3 class='mb-5' class='text-center'>Выберите службу</h3>

<form  method="post" action="" id="f1">
<input type="hidden" name="save" value="1">
<input type="hidden" name="crash_id" value="<?php echo $this->crash->ID; ?>">
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
            <?php echo $this->crash->equipment()->path(); ?>
        </div>
    </div>
    <div class="mb-3 row">
        <div class='col-2'>Комменатрий</div>
        <div class="col-10">
            <?php echo $this->crash->DESCRIPTION; ?>
        </div>
    </div>

    <div >
        <input type="submit" class='btn btn-primary' value="Привлечь службы">
    </div>

</form>
