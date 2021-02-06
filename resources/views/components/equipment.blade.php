<?php
/**
 * @param Equipment|int|null $equipment
 */

$value = empty($equipment) ? '' : (is_a($equipment, \App\Models\Equipment::class) ? $equipment->id : $equipment);
?>

<equipment
    value="{{ $value }}"
></equipment>