<?php $id = $values['ID'] ? $values['ID'] : uniqid(); ?>
<tr data-id="<?php echo $id; ?>">
    <td class="text-center align-middle"><a href="#" onclick="operationGroupCopy(this); return false;"><img src="images/copy.svg" width="20"></a>
	<br><br>
	<a href="#" onclick="operationGroupRemove(this); return false;"><img src="images/x.svg"></a>
	</td>
    <td class="p-4"><input type=hidden name="equipment[<?php echo $id; ?>]" value="<?php echo $values['EQUIPMENT_ID'] ?? '' ?>"></td>
    <td class="works"></td>
    <td><textarea class="form-control" name="NAME[<?php echo $id; ?>]" required onfocus="$(this).parent().find('.works input').prop('checked', false)"><?php echo $values['NAME'] ?? '' ?></textarea></td>
    <td>
        <select name="TYPE_OPERATION[<?php echo $id; ?>]" required class="custom-select form-select" required>
            <option value="0" disabled selected hidden>Выберите</option>
            <?php foreach(Operation::getEnumList('TYPE_OPERATION') as $typeId => $typeName) { ?>
                <option value="<?php echo $typeId; ?>" <?php if($values['TYPE_OPERATION_ENUM'] == $typeId) echo "selected";?>><?php echo $typeName; ?></option>
            <?php } ?>
        </select>
    </td>
    <td>
    <?php if($date) { ?>
        <input type="text" class="form-control text-center" value="<?php echo $date; ?>" readonly>
    <?php } else { ?>
        <input type="text" name="PLANNED_DATE[<?php echo $id; ?>]" value="<?php echo $values['PLANNED_DATE'] ?? '' ?>" class="form-control text-center bg-white" onClick="showCalendar(this);" readonly required>
    <?php } ?>
    </td>
	<td>
			<a href="javascript:void(0)" onClick="showDetailList('<?php echo $id ?>')" class="btn btn-outline-primary mb-3">Списать ТМЦ</a><br>
			<a onClick="noWriteOff('<?php echo $id ?>')" href="javascript:void(0)" class="btn btn-outline-primary mb-3">Без списания</a><br>
			<a onClick="delayWriteoff('<?php echo $id ?>'); return false;" href="#" class="btn btn-outline-primary mb-3">Отложить списание</a>
	</td>
	<td id='op<?php echo $id ?>'>
</tr>