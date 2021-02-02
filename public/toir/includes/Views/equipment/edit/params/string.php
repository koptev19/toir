<?php
/**
 * @param string $name
 * @param Equipment $node
 */

?>
<input type="text" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($node->$name); ?>" class="form-control">
