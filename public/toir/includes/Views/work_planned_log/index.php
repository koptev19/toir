<h4 class='text-center'>
    Журнал плановых операций
	<a href="#" class='ml-3' onclick="self.print(); return false;"><img src="./images/print.svg"></a>
</h4>

<?php
$this->view('work_planned_log/filter', compact('limit', 'services', 'filter'));
?>

<table class="table table-bordered table-sm table-hover">
    <thead>
        <tr class='text-center'>
            <th><div>Наименование оборудования</div></th>
            <th><div>Название операции</div></th>
            <th><div>Периодичноcть</div></th>
			<th><div>Рекомендации</div></th>
	        <th><div>Тип операции</div></th>
			<th><div>Действия</div></th>
        </tr>
    </thead>
    <tbody>
		<tr id='table' data-id=0></tr>
    </tbody>
</table>


<div id='new-row-op' style='display:none'>
	<table>
		<tr data-parentid={parentId} data-id={id}>
             <td {rowspan} style='padding-left:{padding}'>
                <div style='display:{haveChild}'>
				<a href='#' class='closeHref {class}' onClick='openClose({id},this,{num}); return false;'>{eqname}</a>
				</div>
				<span class={class}>{eqnamenochild}</span>	
            </td>
		</tr>
	</table>
</div>

<div id='new-row-op-empty' style='display:none'>
	<table>
		<tr data-parentid={parentId} data-id={id}>
             <td {rowspan} style='padding-left:{padding}'>
                <div style='display:{haveChild}'>
				<a rel=1 href='#' class='closeHref {class}' onClick='openClose({id},this,{num}); return false;'>{eqname}</a>
				</div>
				<span class={class}>{eqnamenochild}</span>	
            </td>
			<td><td><td><td><td>
		</tr>
	</table>
</div>


<div id='new-row' style='display:none'>
	<table>
			<tr data-parentid={parentId} data-id={id}>
			<td>{opname}</td>
            <td class='text-center'>{period}</td>
            <td>{recomendation}</td>
            <td class='text-center'>
			{type}
			</td>
			<td class='text-center'>

						<div style="display:{planned}">
						<a href="edit_operation.php?operation_id={opid}" target="_blank"><img src="./images/pencil.svg"></a>
						
						<a href="del_operation.php?id={opid}" onclick="return confirm('Удалить?')" class="ml-3"><img src="./images/x.svg"></a>
						</div>
						
						<div style="display:{work}">
						<a href='add_work.php?action=edit&work_id={opid}' target='_blank'>
						<img src="./images/pencil.svg">
						</a>
						<a class="ml-3" href="add_plan.php?action=copyFromWork&work_id={opid}" target="_blank"><img src="./images/copy.svg"  width=16 height=16 ></a>
						<a href='add_work.php?action=delete&work_id={opid}' target='_blank' onclick="return confirm('Удалить?')" class="ml-3">
						<img src="./images/x.svg">
						</a>
						</div>
			</td>
        </tr>
		</table>
</div>

<style>
	a.openHref::after{
		content: "";
		width: 30px;
		height: 30px;
		background: url("images/chevron-down.svg") 0 0 no-repeat;
		margin-left: 5px;
		position: absolute;
		display: inline-block;
	}

	a.closeHref::after{
		content: "";
		width: 30px;
		height: 30px;
		background: url("images/chevron-up.svg") 0 0 no-repeat;
		margin-left: 5px;
		position: absolute;
		display: inline-block;
	}
</style>


<script>
	
	function openClose(id,el,num){
		if($(el).hasClass("openHref")){
			$(el).addClass("closeHref");
			$(el).removeClass("openHref");
			closeBranch(id);
		}else{
			$(el).addClass("openHref");
			$(el).removeClass("closeHref");
			returnChildren(id,el,num);
		}
	}
	
	function closeBranch(id){
		if($("tr[data-parentid='"+id+"']").html()){
		   $("tr[data-parentid='"+id+"']").each(function(index,el){	
			closeBranch($(el).data("id"));
		   });
		}
		$("tr[data-parentid='"+id+"']").remove();
	}
		
	function returnChildren(id,el,num){
		
		$.ajax({
        type: "POST",
        url: "work_planned_log.php?"+$("#filterForm").serialize(),
        data: {
            action: 'getChildren',
            id: id,
			
        },
        dataType :'json',
        success: function (data) {
			elem=$("tr[data-id='"+id+"']").last();
			console.log(data);
			for (const [key, value] of Object.entries(data.children)) {
				
		
				var sh = 1;
				if(data.operations && data.operations[value.ID] !== undefined) {
					
					var opHtml ="";
					for (const [key, operation] of Object.entries(data.operations[value.ID])) {
						 opHtml = opHtml + $("#new-row>table>tbody").html()
			            .replace(/\{opname\}/g, operation.NAME)
						.replace(/\{period\}/g, operation.PERIODICITY ? operation.PERIODICITY : "")
						.replace(/\{recomendation\}/g, operation.RECOMMENDATION)
						.replace(/\{planned\}/g, operation.PERIODICITY ? "" : "none")
						.replace(/\{work\}/g, operation.PERIODICITY ? "none" : "")
						.replace(/\{type\}/g, operation.TYPE)
				        .replace(/\{parentId\}/g, id)
						.replace(/\{id\}/g, value.ID)
						.replace(/\{opid\}/g, operation.ID)	
						sh++;
					}
					if(sh==2) console.log(data.operations[value.ID]);
					var eqHtml = $("#new-row-op>table>tbody").html();
					elem.after(eqHtml.replace(/\{eqname\}/g, value.NAME)
							.replace(/\{rowspan\}/g, "rowspan='"+sh+"'")
	                		.replace(/\{num\}/g, sh-1)
							.replace(/\{id\}/g, value.ID)
	 						.replace(/\{padding\}/g, (value.LEVEL*10)+"px")
							.replace(/\{parentId\}/g, id)
							.replace(/\{haveChild\}/g, value.HASCHILDREN ? "" : "none")
							.replace(/\{eqnamenochild\}/g, value.HASCHILDREN ? "" : value.NAME)
							.replace(/\{class\}/g, value.CLASS)
					) 
					elem=$("tr[data-id='"+value.ID+"']").last();		
					elem.after(opHtml);
					elem=$("tr[data-id='"+value.ID+"']").last();	
				}else{
					var eqHtml = $("#new-row-op-empty>table>tbody").html();
					elem.after(eqHtml.replace(/\{eqname\}/g, value.NAME)
						.replace(/\{rowspan\}/g, "")
						.replace(/\{num\}/g, sh-1)
						.replace(/\{id\}/g, value.ID)
	 					.replace(/\{padding\}/g, (value.LEVEL*10)+"px")
						.replace(/\{parentId\}/g, id)
						.replace(/\{haveChild\}/g, value.HASCHILDREN ? "" : "none")
						.replace(/\{eqnamenochild\}/g, value.HASCHILDREN ? "" : value.NAME)
						.replace(/\{class\}/g, value.CLASS)
					) 
					elem=elem.next();
					console.log(elem);
				}
			}
					
		}
    });
	}	

	$(document).ready(function() {
	  returnChildren(0,0,0);
	});
</script>

