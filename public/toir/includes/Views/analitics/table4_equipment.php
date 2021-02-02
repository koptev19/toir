<tr class="text-center table4-equipment-<?php echo $equipment->PARENT_ID; ?>" data-id="<?php echo $equipment->ID; ?>">
    <td class="text-left" style="padding-left:<?php echo 10 + 30 * ($equipment->LEVEL - 1)?>px;">
        <a href="#" onclick="table4ShowChildrenEquipments(<?php echo $equipment->ID; ?>, <?php echo $equipment->WORKSHOP_ID; ?>, '<?php echo $this->dateFrom; ?>', '<?php echo $this->dateTo; ?>', this); return false;"><?php echo $equipment->NAME; ?></a>
		<?php if (count($equipment->CHILDREN)){?>
			<img src="images/chevron-down.svg" class="table4-down-<?php echo $equipment->ID; ?>" >
			<img src="images/chevron-up.svg" class="table4-up-<?php echo $equipment->ID; ?>" style="display:none">
		<?php } ?>
    </td>
    <td><?php echo $this->analitic->table4_2Plans($equipment); ?></td>
    <td><?php echo $this->analitic->table4_2EquipmentTimeOperationPlanPlan($equipment); ?></td>
    <td><?php echo $this->analitic->table4_2EquipmentTimeOperationPlanFact($equipment); ?></td>
    <td><?php echo $this->analitic->table4_2Operations($equipment); ?></td>
    <td><?php echo $this->analitic->table4_2EquipmentTimeOperationPlan($equipment); ?></td>
    <td><?php echo $this->analitic->table4_2EquipmentTimeOperationFact($equipment); ?></td>
</tr>
