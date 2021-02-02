<?php
/**
 * @param string $name
 * @param Equipment $node
 */

?>
<? $date=($node->$name)? date("Y-m-d",strtotime($node->$name)):"";?>
<input type="date" name="<?php echo $name; ?>" value="<?php echo $date; ?>" class="form-control w-auto">
