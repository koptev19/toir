<?php
/**
 * @param string $name
 * @param array $items
 * @param Equipment $node
 */
?>

<select name="<?php echo $name; ?>" class="form-select">
    <option value=""></option>
    <?php foreach($items as $itemKey => $itemValue) { ?>
        <option value="<?php echo $itemKey; ?>" <?php if($itemKey == $node->$name) echo "selected"; ?>><?php echo $itemValue; ?></option>
    <?php } ?>
</select>
