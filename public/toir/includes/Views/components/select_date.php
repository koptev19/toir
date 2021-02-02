<?php
/**
 * @param $lineId 		// id линии
 * @param $serviceId 	// id службы
 * @param $monthsCount 	// количество показываемых месяцев
 * @param $fieldName 	// название поля, куда вставляется дата
 * @param $excludeDate 	// Исключить дату (в любом формате)
*/

$lineId = intval($lineId ?? 0);
$serviceId = intval($serviceId ?? 0);

$monthsCount = intval($monthsCount ?? 3);
$monthsCount = max($monthsCount, 1);

$fieldName = $fieldName ?? 'PLANNED_DATE';

$createStopDate = $createStopDate ?? [0, 1];
$showPastMonth = $showPastMonth ?? false;

$excludeDate = $excludeDate ?? null;

?>

<?php if(in_array(1, $createStopDate)) { ?>
    <input type="radio" name="createstopdate" class='mr-2' value=1 checked >Дата остановки
<?php } ?>
<?php if(in_array(0, $createStopDate)) { ?>
    <input type="radio" name="createstopdate" class='ml-4 mr-2' value=0>Дата без остановки линии<br> 
<?php } ?>

    <?php
	$startMonth = (int)date("m", mktime(0, 0, 0, date('m')-1, date('d'), date("Y")));
	$year =   (int)date("Y", mktime(0, 0, 0, date('m')-1, date('d'), date("Y")));
	$hideFirstMonth=false;
	if($showPastMonth){
		$curentday=(int)date("Y-m-d", mktime(0, 0, 0, date('m')-1, date('d'), date("Y")));
	}else{
			for($i=1;$i<4;$i++){
			$day=mktime(0, 0, 0, date('m'), date('d')-$i, date("Y"));
			if (date("N", $day)<6){
				$curentday=date("Y-m-d", $day);
					if($startMonth!=(int)date("m", $day)){
						$hideFirstMonth=true;
					}
				break;
			}
		}
	}	

    for($monthVar = $startMonth; $monthVar < $startMonth + $monthsCount; $monthVar++) {
        $month = $monthVar > 12 ? $monthVar - 12 : $monthVar;
        if($monthVar == 13) {
             $year++;
        }
		?>
		<div class="<?php echo ($monthVar == $startMonth && $hideFirstMonth)? "hiddenmonth":"";?>">
		<h4 class='text-center'><? echo monthName($month); ?></h4>
		<table class="dateselector table table-bordered table-sm">
			 <tr class='text-center'>
			 <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $month, 1, $year)); $i++){
			    $class = isWeekend($i, $month, $year) ? 'table-danger' : '';
				$cellDate = date("Y-m-d", mktime(0, 0, 0,$month, $i, $year));
				$class.= (strtotime($cellDate) < strtotime($curentday) || $cellDate === $excludeDate) ? " inactive1" : " topdays";
				$class .= $cellDate === date("Y-m-d", strtotime($excludeDate)) ? " exclude-date" : "";
			    ?>
			    <td class="<?php echo $class; ?>" style="width:3%" id="<?php printf("h%d%02d%02d", $year, $month, $i);?>">
			        <?php echo $i; ?>
                </td>           
            <?php } ?>
			<tr class='text-center'>
            <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $month, 1, $year)); $i++) {
				 $class = isWeekend($i, $month, $year) ? 'table-danger' : '';
				 $cellDate = date("Y-m-d", mktime(0, 0, 0,$month, $i, $year));
				 $class .= (strtotime($cellDate) < strtotime($curentday) || $cellDate === $excludeDate) ? " inactive" : " days";
				 $class .= $cellDate === date("Y-m-d", strtotime($excludeDate)) ? " exclude-date" : "";
				 ?>	
				<td rel='<?php echo $class; ?>' class="<?php echo $class; ?>" id="<? printf("%d%02d%02d", $year, $month, $i);?>" ></td>
            <?}?>
	  	</table>
		</div>
    <?php } ?>

 	<input type="hidden" name="<?php echo $fieldName; ?>" id="selecteddate">

<style>
 .days{cursor:pointer;height:35px}
 .highlight{background-color:#dbecdf}
 .selected {background-color:#C3E6CB}
 .table .inactive,.table .inactive1,.table .exclude-date {background-color:#DEDEDE!important;color:#a09494;cursor:default}
 .hiddenmont{display:none}
</style>


<script>	
var lineId = <?php echo $lineId; ?>;
var serviceId = <?php echo $serviceId; ?>;

 

  function showStopDateLine(){
	initTables();
	resetSelectedDate();
    if (Number(lineId) > 0)
    {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: 'getStops',
                LINE_ID: lineId,
				SERVICE_ID: serviceId,
            },
            dataType :'json',
            success: function (data) {
				$.each(data, function( index, value ) {
                   let el= $("#"+index.replace(/[\-]/g,""));
				   let elh= $("#h"+index.replace(/[\-]/g,""));
				   el.html("V");
				    <?php if($showPastMonth){ ?> 
						 if(value.stage == <?php echo DateProcess::STAGE_REPORT_DONE; ?>){ 
							if(<?php echo date("Ymd"); ?>>Number(index.split("-")[0]+index.split("-")[1]+index.split("-")[2])){
								elh.removeClass("topdays");			
								el.removeClass("days");			
								elh.addClass("inactive1");			
								el.addClass("inactive");			
							}
						}
					<?php }else{ ?>
					  if(el.hasClass("inactive") && !el.hasClass('exclude-date')){
						if(value.stage < <?php echo DateProcess::STAGE_REPORT_DONE; ?>){
						 elh.removeClass("inactive1");			
						 el.removeClass("inactive");			
						 elh.addClass("old topdays");		
						 el.addClass("old days");			
						 el.parent().parent().parent().parent().show();
						}
	   				}
					<?php } ?>
				});
				$("#dateselector").animate({height: 'show'}, 500);
            }
         });
    }	
}

function hideStopDateLine() {
	resetSelectedDate();
	$('input[type=radio][name=createstopdate][value=1]').prop('checked', true);
	$("#dateselector").animate({height: 'hide'}, 500);
}

function resetSelectedDate(){
	  $( "td.days" ).removeClass("table-info");
      $( "td.topdays" ).removeClass("table-info");
	  $("#selecteddate").val("");
}

function initTables(){
	$("td.days").html("");
	$("td.inactive").html("");
	$("td.old.days").addClass("inactive");
	$("td.old.topdays").addClass("inactive1");
	$(".hiddenmonth").hide();
}


if(typeof(clickondate)!='function'){
	var clickondate=function(el){
				resetSelectedDate();
				$(el).addClass("table-info");	
				$("#h"+$( el).attr('id')).addClass("table-info");	
				var date=$( el).attr('id').slice(6,8)+"."+$( el).attr('id').slice(4,6)+"."+$( el).attr('id').slice(0,4);
				$("#selecteddate").val(date); 
	}
}

$( document ).ready(function() {
	$( "td.days" ).hover(
	  function() {
	   // if($( this ).html()!="V") $( this ).html("+");		
		$( this ).addClass("table-active");
		$("#h"+$( this ).attr('id')).addClass("table-active");
	  }, function() {
	    //if($( this ).html()!="V") $( this ).html("");
		$( this ).removeClass("table-active");
		$("#h"+$( this ).attr('id')).removeClass("table-active");
	  }
	  );

	  $( "table.dateselector td" ).click(
		  function(){
		  	if((!$(this).hasClass("days"))&&(!$(this).hasClass("old"))) return false;
			if ($(this).hasClass("exclude-date")) return false;
			clickondate($(this));
	  });



	  $('input[type=radio][name=createstopdate]').change(function() {
		    resetSelectedDate();
			if (this.value == '1') {
			    showStopDateLine()
		    }
			else{
				initTables();
			}
	   });
});
</script>