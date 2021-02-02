var showedEquipment = {};

function table4ShowChildrenEquipments(equipmentId, workshopId, dateFrom, dateTo, link)
{
	$(".table4-up-" + equipmentId).toggle();
	$(".table4-down-" + equipmentId).toggle();
	
	if(showedEquipment[equipmentId]) {
        closeBranch(equipmentId);
		return;
	}


	$.ajax({
        type: "POST",
        url: "analitics.php",
        data: {
            table4Equipment: 1,
            workshop: workshopId,
            parent: equipmentId,
            date_from: dateFrom,
            date_to: dateTo,
        },
        success: function ( data ) {
			$(link).parent().parent().after(data);
			showedEquipment[equipmentId] = 1;
        }
    });
}

function closeBranch(equipmentId){
	if($(".table4-equipment-" + equipmentId).html()){
		closeBranch($(".table4-equipment-" + equipmentId).data("id"));
	}
	$(".table4-equipment-" + equipmentId).remove();
    delete showedEquipment[equipmentId];
}
