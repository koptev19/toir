<?php
/**
 * @param Equipment|int|null $equipment
 * @param boolean|null $required
 */

$value = empty($equipment) ? '' : (is_a($equipment, \App\Models\Equipment::class) ? $equipment->id : $equipment);
$required = $required ?: false;
?>

<equipment
    value="{{ $value }}"
    :required="{{ $required ? 'true' : 'false' }}"
></equipment>