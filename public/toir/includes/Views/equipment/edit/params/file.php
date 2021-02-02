<div class="row">
    <?php if($node->$name) { ?>
    <div class="col-1">
        <?php $this->view('equipment/show/params/file', compact('node', 'name')); ?>
    </div>
    <?php } ?>
    <div class="col-11">
        <input type="file" name="<?php echo $name; ?>" class="form-control mt-2" />
    </div>
</div>

