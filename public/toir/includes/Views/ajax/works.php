<?php foreach($works as $work) { ?>
    <label><input type="radio" value="<?php echo $work->ID; ?>"> <?php echo $work->name; ?></label>
<?php } ?>
