<?php foreach($services as $service) { ?>
    <div class="custom-control custom-radio">
        <input type="radio" id="service-<?php echo $service->ID; ?>" name="service" class="custom-control-input" value="<?php echo $service->ID; ?>">
        <label class="custom-control-label" for="service-<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></label>
    </div>
<?php } ?>
<input type="hidden" name="source" value="<?php echo Operation::SOURCE_GROUP_CRASH; ?>">
<input type="hidden" name="crash" value="<?php echo $this->crash->ID; ?>">