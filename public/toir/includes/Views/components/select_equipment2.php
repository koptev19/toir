<?php
/**
 * @param Equipment|int $selectedBranch		// Выбранная ветка
 * @param Equipment|int $equipment		// Выбранное оборудование
 * 
 */
$GLOBALS["APPLICATION"]->AddHeadScript(TOIR_PATH . "scripts/equipment_tree_select.js");
$GLOBALS["APPLICATION"]->SetAdditionalCSS(TOIR_PATH . "styles/equipment.css", true);

?>

<?php if(!isset($multiply)){ ?>
	<div id='selectedEquipment' class='border rounded p-2' style="cursor:pointer;" onClick="showTree()">Загружаю список оборудования...</div>
	<input type=hidden name="workshop" id="workshop">
	<input type=hidden name="line" id="line">
	<input type=hidden name="<?php echo $fieldName ?? "equipment" ?>" id="equipment">
<?}?>

<div class="modal fade" tabindex="-1" id='equipmentWindow'>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class='modal-body'>
		    <div style='display:block' id='container0' el="0" class='content mb-3'></div>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
			</div>	
		</div>
		
	</div>
</div>

<script>
var branch= [];
var parents=[];
var currentHref={};
var cashedParents =[];
<?php if(isset($multiply)){ ?>
	var multiply = true;
<?}else{?>
	var multiply = false;
<?}

if ($selectedBranch)
{
	$elementBranch = Equipment::find($selectedBranch);
	$parents = $elementBranch->parents();
	echo "branch.push(".$selectedBranch.");";
	foreach($parents as $k=>$v )
	{
		echo "branch.push(".$k.");";
	}
			
	
}

if($equipment){
	$element = (is_object($equipment))? $equipment : Equipment::find($equipment);
	$parents = $element->parents();
	foreach($parents as $k=>$v )
	{
		echo "parents[".$k."]=1;";
	}
}
?>

var selectedElement=<?php echo $element->ID ?? 0; ?> 
var maxLevel=<?php echo $maxLevel ?? 0; ?> 

$( document ).ready(function() {
	if(!multiply){
		getNodes(0);
		if(!selectedElement){
			$('#selectedEquipment').html('<a href="#">Выбрать оборудование</a>');
		}
	}else{
	$('.equipment-select-input').each(function( index, element ) {
		makeEquipmentHref($(element))		
	});
	}
});



function clickOnItem(el){
	var n=$(el).parent().attr("el");
	var path="<a href='#'>"+$("#name"+n).html()+"</a>";
	var elements=[n];
	var elementsCashed=[];
	$("#workshop").val("").trigger('change');
	$("#line").val("").trigger('change');
	$("#equipment").val("").trigger('change');
	$(".item").removeClass("selected");
	$(el).parent().addClass("selected");
	while($("#n"+n).parent().attr("el")!=0){
	   n=$("#n"+n).parent().attr("el");
	   elements.push(n);	
	   elementsCashed[n] = 1;
	   path=$("#name"+n).html()+" > "+path;
	}
	cashedParents[elements[0]]=elementsCashed;
	console.log(elements);
	if(maxLevel){
		if (elements.length>maxLevel) return false;
	}	
	if(!multiply){
		$("#workshop").val(elements[elements.length-1]).trigger('change');
		$("#equipment").val(elements[0]).trigger('change');
		if(elements.length>1) $("#line").val(elements[elements.length-2]).trigger('change');
		$('#selectedEquipment').html(path);
	}else{
		currentHref.html(path);
		let lnk = currentHref.data("lnk");
		console.log(lnk);
		console.log(elements);
		$('#'+lnk).val(elements[0]);
	}
	$('#equipmentWindow').modal('hide');
}

function makeEquipmentHref(element){
	if(element.next().hasClass("equipment-select-modal")){
		let lnk = "lnk"+Math.floor(Math.random() * 1000000);
		element.attr("id",lnk);
		element.next(".equipment-select-modal").data("lnk",lnk);
		return false;
	}	
	if(element.val()){
		$.ajax({
            type: "POST",
            url: "ajax.php",
			data:{
			    action: 'getParents',
				id: $(element).val()
			},
            dataType :'json',
            success: function ( data ) {
		         let lnk = "lnk"+Math.floor(Math.random() * 1000000);
				 let ar =[];
				element.attr("id",lnk);
				element.after('<a href="#" data-lnk="'+lnk+'" class="equipment-select-modal" onClick="clickOnEquipmentHref(this); return false">'+data.path+'</a>');
				for (var prop in data.parents) {
				  ar[prop] = 1;
				}
				cashedParents[$(element).val()] = ar;
            }
        });
		
	}else{
		let lnk = "lnk"+Math.floor(Math.random() * 1000000);
		element.attr("id",lnk);
		element.after('<a href="#" data-lnk="'+lnk+'" onClick="clickOnEquipmentHref(this); return false" class="equipment-select-modal">Выбрать оборудование</a>');
	}
}

function clickOnEquipmentHref(el){
				console.log("clickOnEquipmentHref");
				currentHref=$(el);
				let lnk = currentHref.data("lnk");
				if($('#'+lnk).val()){
					parents=cashedParents[$('#'+lnk).val()];
					selectedElement = $('#'+lnk).val();
					getNodes(0);
					console.log("ss");
					console.log(parents);
					console.log(selectedElement);
				}else{
					parents = [];
					selectedElement = false;
					getNodes(0);
				}
				$('#equipmentWindow').modal('show');
}

function showTree() 
{
	$('#equipmentWindow').modal('show');
	$("#equipmentWindow").appendTo("body")
}

</script>