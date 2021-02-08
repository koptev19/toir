<?php
/**
 * @param Equipment|int|null $equipment
 * @param boolean|null $required
 */

$selected = [];
$value = empty($equipment) ? '' : (is_a($equipment, \App\Models\Equipment::class) ? $equipment->id : $equipment);
if($value) {
    $equipments = [$equipment];
    $e = $equipment;
    while($e->parent) {
        $equipments[] = $e->parent;
        $e = $e->parent;
    }

    return response()->json([
        'items' => new EquipmentResource(collect(array_reverse($equipments)))
    ]);
}
$required = $required ?: false;
?>

<equipment
    value="{{ $value }}"
    :required="{{ $required ? 'true' : 'false' }}"
    :selected="{{ json_encode(new EquipmentResource(collect(array_reverse($selected))) }}"
></equipment>