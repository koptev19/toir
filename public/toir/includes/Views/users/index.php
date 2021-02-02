<?php

function PrintCheckBox($name,$value,$id,$checked,$display=false,$disabled=false){ ?>
	<input  style="cursor:pointer; <?php echo ($display) ? "display:none" : "block" ?>" <?php echo ($checked) ? "checked" : "" ?> <?php echo ($disabled) ? "disabled" : ""  ?> type="checkbox" id="<?php echo $id;?>"  name="<?php echo $name;?>" value='<?php echo $value;?>' onchange="checkBoxChange(this)" >
<?php }?>

<form method="post" action="users.php" id='form'>
<input type="hidden" id="result" name="ACTION" value='store'>
<div id="deleted-users" style='display:none'>
</div>
           
<div class="table-responsive mb-3">
<table class="table table-bordered table-hover text-center" >
    <thead>
        <tr id='rowHead'>
            <th class="text-left" rowspan=2>ФИО</th>
            <th rowspan=2>Администратор</th>
            <th rowspan=2 style="width:400px" ?>Службы</th>
			<th colspan=<?php echo count($workshops)+1; ?>>Цеха</th>
			<th rowspan=2>&nbsp;</th>
		  </tr>
		<tr>
			<th> Все цеха </th>
			<?php foreach($workshops as $workshop){?>
				<th><?php echo $workshop->NAME; ?>
			<?php }?>
			</tr>

    </thead>
    <tbody id='table-users'>
    <?php foreach($connectedUsers as $user) { 
		$itself = ($user->id == UserToir::current()->id);
		$userServices = $user->SERVICE_ID ?? [];
		$userWorkshops = $user->WORKSHOP_ID ?? [];
		$isAdmin = $user->IS_ADMIN;
		$allWorkshops = $user->ALL_WORKSHOPS;
		?>
        <tr data-userid="<?php echo $user->ID ?>">
        	<?php if(!$itself){ ?>
			<input type=hidden name="users[<?php echo $user->ID ?>]" value="1">
			<input type=hidden class="rootUser" name="rootUser" value="<?php echo $user->USER_ID ?>">
			<?php }?>
			<td class="text-left"> <?php echo $user->NAME ?> </td>
			<td> <?php printCheckBox("admin[".$user->ID."]",1,"adm".$user->ID, $isAdmin,false,$itself); ?></td>
			<td class="text-left">
			<select name='services[<?php echo $user->ID ?>][]' id="service<?php echo $user->ID; ?>" multiple style="width:100%">
			<?php foreach($services as $service){?>
				<option value="<?php echo $service->ID; ?>" <?php echo in_array($service->ID,$userServices) ? "selected" :"" ?> >
					<?php echo $service->name ?>
				</option>
			
			<?php }?>
			</select>
			<td> <?php printCheckBox("wsAll[".$user->ID."][all]","1","wss".$user->ID, $allWorkshops,$isAdmin); ?> </td>	
			<?php foreach($workshops as $workshop){?>
				<td>
				<?php printCheckBox("workshops[".$user->ID."][]",
									$workshop->ID,
									"ws".$user->ID.$workshop->ID,
									in_array($workshop->ID,$userWorkshops),
									($isAdmin||$allWorkshops ) 
									)?>
				</td>

			<?php } ?>
			<td>
			<?php if(!$itself){ ?>
			<a href="#" onclick="deleteUser(this);return false"> <img src="images/x.svg"></a> 
			<?php } ?>
        </tr>
	 <?php }?>
	</tbody>
	<tfoot>
		<tr id="new-user-list" style="display:none">
			<td colspan=100% class="text-left"> 
			  <div>
				<select name="USER_ID" id="new-user-select" class="form-control form-select w-auto">
			      <option value="">Выберите пользователя</option>
					<?php foreach($notConnectedUsers as $user) { 
						if($key == $GLOBALS['USER']->getId()) continue;
					?>
			            <option class="newUserOption" id="newUserOption<?php echo $user->id; ?>" value="<?php echo $user->id; ?>" ><?php echo $user->fullname; ?></option>
			        <?php } ?>
			    </select>
				</div>
				<div class="mt-3">
					<a class="btn btn-primary" href="#" onCLick="addUser()">добавить</a>&nbsp;&nbsp
					<a href="#" class="btn btn-outline-secondary ms-5" onClick="showSelectUser()">отмена</a>
				</div>
			</td>
		</tr>
		<tr id="new-user-select-href">
		<td class="text-left" colspan=100%> <a class="btn btn-outline-primary" href="#" onClick="showSelectUser()">Добавить</a> 
		</tr>
	</tfoot>
</table>
</div>

    
<div class='mt-4 text-center'>
     <!--<a href="#" onClick="submitForm()" class="btn btn-info ml-5">Сохранить</a>-->
	<button type="submit" class="btn btn-primary ml-5">Сохранить</button>	 
	<a href="users.php"  class="btn btn-outline-secondary">Отмена</a> 
</div>
</form>


<div id="newUser" style="display:none">
		<table id="hidden-row">
		<tr data-newuser="1">
        	<input type=hidden class="rootUser" name="rootUser" value="{id}">
			<input type=hidden name="usersNew[{id}]" value="{name}">
			<td class="text-left" > {name} </td>
			<td> <?php printCheckBox("adminNew[{id}]",1,"adm{id}"."{id}",""); ?></td>
			<td class="text-left">
			<select name='servicesNew[{id}][]' id="serviceNew{id}" multiple style="width:100%">
			<?php foreach($services as $service){?>
				<option value="<?php echo $service->ID ?>"><?php echo $service->name ?></option>
			
			<?php } ?>
			</select>
			<td> <?php printCheckBox("wsAllNew[{id}]","1","wss{id}"."",""); ?> </td>	
			<?php foreach($workshops as $workshop){?>
				<td>
				<?php printCheckBox("workshopsNew[{id}][]",
									$workshop->ID,			
									"workshopsNew{id}".$workshop->ID,
									""); ?>
				</td>

			<?php } ?>
			<td><a href="#" onclick="deleteUser(this);return false"> <img src="images/x.svg"></a> 
        </tr>
		</table>
</div>

<script>

var numWorker = 0;
var classDiv = '';
function addUser() {
	let id = $( "#new-user-select").val();
	if(!id) return false;
	let rowHtml = $("#hidden-row>tbody").html()
            .replace(/\{name\}/g, $( "#new-user-select option:selected" ).text())
            .replace(/\{id\}/g, id);
    $("#table-users").append(rowHtml);
	$('#serviceNew'+id).select2();
	$('#new-user-select option:first').prop('selected', true);
	
	$("#new-user-list").toggle();
	$("#new-user-select-href").toggle();
}

function showSelectUser() {
	$(".newUserOption").show();
	$(".rootUser").each(function(index,element){
		if ($(element).val() != "{id}"){
		$("#newUserOption"+$(element).val()).hide();
		console.log($(element).val());
		console.log($("#newUserOption"+$(element).val()));
		}
	});	
	$("#new-user-list").toggle();
	$("#new-user-select-href").toggle();
}

function deleteUser(el){
	var tr=$(el).parent().parent();
	if(tr.data("newuser")){
		tr.remove();
	}else{
		$("#deleted-users").append("<input type='hidden' name='delUsers[]' value='"+tr.data("userid")+"'>");
		tr.remove();
	}
}

function changeLabel(el){
	/*if (el.checked){
		$(el).parent().find('label').html('Да'); 
	}else{ 
		$(el).parent().find('label').html('Нет');
	}*/
}

function checkBoxChange(el) {
	changeLabel(el);
	
	if($(el).attr("name").indexOf("admin")>-1) {
		inactiveAll(el,el.checked);
	}	
	
	if($(el).attr("name").indexOf("wsAll")>-1) {
		inactiveAllWorkshop(el,el.checked);
		console.log("1");	
	}	

}

function inactiveAllWorkshop(el,checked){
	var tr= $(el).parent().parent();
	tr.children("td").each(function(index,element){
		var chk=$(element).find("input[type='checkbox']");
		if(chk.length){
		  if (chk.attr("name").indexOf("workshop") > -1)
		  {
			if(checked){
				chk.prop("checked",false);
				chk.hide();
			}else{ 
				chk.show();
			}
		  }
		}
	});	
}


function inactiveAll(el,checked){
	var tr= $(el).parent().parent();
	tr.children("td").each(function(index,element){
		var chk=$(element).find("input[type='checkbox']");
		if(chk.length){
		  if (chk.attr("name").indexOf("admin") <= -1)
		  {
			if(checked){
				chk.prop("checked",false);
				chk.hide();
			}else{ 
				chk.show();
			}
		  }
		}
		var sel=$(element).find("select");
		if(sel.length){
		  	if(checked){
				sel.val([]);
				sel.trigger("change");
				$(element).find('span.selection').hide();
			}else{
				$(element).find('span.selection').show();
			}	
		}
	});	
}

$(document).ready(function() {
    <?php foreach($connectedUsers as $user){ ?> 
		$('#service<?php echo $user->ID; ?>').select2();
		<?php if($user->IS_ADMIN) { ?>
			$('#adm<?php echo $user->ID; ?>').trigger("change");
		<?php }
	}?>
	
	/*
	$(window).bind(
    "beforeunload", 
    function() { 
        return confirm(); 
	});
	*/
});

</script>