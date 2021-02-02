<?php
/**
 * @param string $name
 * @param Equipment $node
 */

$enum = Equipment::getEnumList($name);

if(!empty($enum)) { ?>
<select name="<?php echo $name; ?>" class="form-control form-select">
    <option value=""></option>
    <?php foreach($enum as $key => $value) { ?>
        <option value="<?php echo $key?>" <?php if($value == $node->$name) echo "selected"; ?>><?php echo $value; ?></option>
    <?php } ?>
</select>
<? } ?>
