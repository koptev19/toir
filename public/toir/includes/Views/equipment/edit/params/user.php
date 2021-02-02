<?php
/**
 * @param string $name
 * @param Equipment|null $node
 */

$requiredSelect = $required ? "required" : '';
?>
<select name="<?php echo $name; ?>" class="form-control form-select" <?php echo $requiredSelect; ?>>
    <?php if(!$required) { ?>
        <option value=""></option>
    <?php } ?>
    
    <?php foreach(UserService::getList() as $user) { ?>
        <option value="<?php echo $user->id; ?>" <?php if($node && $user->id == $node->$name) echo "selected"; ?>><?php echo $user->fullname; ?></option>
    <?php } ?>
</select>
