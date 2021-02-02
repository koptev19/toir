<?php
/**
 * @param string $name
 * @param Equipment $node
 */

$user = UserToir::find($node->$name);

if($user) {
    echo $user->fullname;
}