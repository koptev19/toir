<?php if($equipment) { ?>
    <input type="hidden" name="WORK_ID[<?php echo $id; ?>]" value="">
    <label class="d-block mb-3"><input type="radio" name="WORK_ID[<?php echo $id; ?>]" value="" <?php if(!$workId || !isset($works[$workId])) echo "checked"; ?> onclick="changeWork(this)"> Единоразовая операция</label>
    <?php foreach($works as $work) { ?>
        <label class="d-block">
            <input type="radio" name="WORK_ID[<?php echo $id; ?>]" value="<?php echo $work->ID; ?>" <?php if($work->id == $workId) echo "checked"; ?> onclick="changeWork(this)" data-recommendation="<?php echo $work->RECOMMENDATION; ?>" data-type="<?php echo $work->TYPE; ?>"> 
            <span><?php echo $work->NAME; ?></span>
        </label>
    <?php } ?>
    <a href="#" onclick="newWork('<?php echo $equipment->ID; ?>', '<?php echo $equipment ? $equipment->path(false) : ''; ?>', '<?php echo $id; ?>'); return false;">Новая операция</a>
<?php } ?>
