<?php
/**
 * @param Equipment|int|null $equipment
 * @param boolean|null $required
 */

$equipment = empty($equipment) ? null : (
    is_a($equipment, \App\Models\Equipment::class) ? $equipment : \App\Models\Equipment::find($equipment)
);

$selected = [];
if($equipment) {
    $selected = array_merge([$equipment->id], $equipment->allParentsId());
}

$required = $required ?: false;
?>

<equipment
    value="{{ optional($equipment)->id }}"
    :required="{{ $required ? 'true' : 'false' }}"
    :selected="{{ json_encode(array_reverse($selected)) }}"
    path="{{ optional($equipment)->full_path }}"
></equipment>