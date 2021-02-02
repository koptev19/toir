<?php
/**
 * @param string $name
 * @param Equipment $node
 * @param array|null $filter = []
 */

if(empty($filter)) {
    $filter = [];
}

$node->$name;
$nodeProperties = $node->getBitrixProperties();

$selectData = [];

if(!empty($nodeProperties[$name]) && !empty($nodeProperties[$name]['LINK_IBLOCK_ID'])) {
    $sort = ['NAME' => 'asc'];
    $filter['IBLOCK_ID'] = $nodeProperties[$name]['LINK_IBLOCK_ID'];
    $res = CIBlockElement::GetList($sort, $filter, false, $navArr, $fields);
    while ($element = $res->GetNextElement()) {
        $selectData[$element->fields['ID']] = $element->fields['NAME'];
    }
}

if(!empty($selectData)) { ?>
<select name="<?php echo $name; ?>" class="form-control form-select">
    <option value=""></option>
    <?php foreach($selectData as $key => $value) { ?>
        <option value="<?php echo $key?>" <?php if($key == $node->$name) echo "selected"; ?>><?php echo $value; ?></option>
    <?php } ?>
</select>
<? } ?>
