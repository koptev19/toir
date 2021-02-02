<?php
/**
 * @param string $name
 * @param Equipment $node
 */


echo $node->$name ? date("d.m.Y", strtotime($node->$name)) : "";?>
