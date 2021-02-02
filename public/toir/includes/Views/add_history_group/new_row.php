<?php $id = uniqid(); ?>
<tr data-id="<?php echo $id; ?>">
    <td class="text-center align-middle"><a href="#" onclick="historyGroupCopy(this); return false;"><img src="images/copy-square.svg"></a></td>
    <td>            
        <input type=hidden name='equipment[<?php echo $id; ?>]' value="<?php echo $values['EQUIPMENT_ID'] ?? '' ?>" class='equipment-select-input'>
    </td>
    <td class="works"></td>
    <td><textarea class="form-control" name="NAME[<?php echo $id; ?>]" required><?php echo $values['NAME'] ?? '' ?></textarea></td>
    <td>
        <select name="TYPE_OPERATION[<?php echo $id; ?>]" required class="custom-select form-select" required>
            <option value="0" disabled selected hidden>Выберите</option>
            <?php foreach(Operation::getTypes() as $typeId => $typeName) { ?>
                <option value="<?php echo $typeId; ?>" <?php if($values['TYPE_OPERATION'] == $typeId) echo "selected";?>><?php echo $typeName; ?></option>
            <?php } ?>
        </select>
    </td>
    <td><input type="text" class="form-control" name="COMMENT[<?php echo $id; ?>]" value="<?php echo $values['COMMENT'] ?? '' ?>" required></td>
    <td><input type="text" class="form-control" name="OWNER[<?php echo $id; ?>]" value="<?php echo $values['OWNER'] ?? '' ?>" required></td>
    <td>
        <input type="text" class="form-control text-center" value="<?php echo $date; ?>" readonly>
    </td>
    <td class="text-center align-middle"><a href="#" onclick="historyGroupRemove(this); return false;"><img src="images/x.svg"></a></td>
</tr>