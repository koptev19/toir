var curentLevel=-1;

function getNodes(parentId, setposition)
{
	$.ajax({
	    type: "POST",
        url: "ajax.php",
        data: {
            action: "getNodes",
			PARENT_ID: parentId
        },
        dataType :'json',
        success: function (data) {
		  	var isContainerEmpty = $("#container" + parentId).html();
			curentLevel++;
			var clickId=0;
		   	$("#container" + parentId).html("");
			$.each(data, function(index, value) {
			   	if(branch.length>curentLevel){
						if (!branch.includes(Number(index))) return
						delete branch[branch.indexOf(Number(index))];
				}	
				if(parents.length){
					if(parents[index]) clickId=index;
				}
				var selected = (selectedElement==index) ? "selected":"";   		
				let node = (value.countChildren == 0) 
					? "" 
					: "<span class='closed plus' id='sn" + index + "' parent='" + index + "'>></span>";
					
					
				let className = 'd-flex node-name' + (value.isLine ? ' text-danger' : "" );;
				$("#container" + parentId).append("<div class='d-flex item py-1 "+selected+"' el='" + index + "' id='n" + index + "'><div class='node d-flex'>" + node + "</div><div class='d-flex node-name " + className + "' id='name" + index + "'>" + value.name + "</div></div><div el='" + index + "' id='container" + index + "' class='content ml-3 pl-1'></div>");
			    $("#sn" + index).click(function(){
					clickOnNode(this);
				});		
				$("#name" + index).click(function(){
					clickOnItem(this);
				});		
			});
			if (clickId) {
				clickOnNode($("#sn" + clickId));
			}
			if($("#name"+selectedElement).html()){
				if(multiply){
					selectedElement =0;
				}else{
					clickOnItem($("#name"+selectedElement));
					selectedElement =0;
				}	
			}
		}
    });    
}


function clickOnNode(el){
	if ($(el).hasClass("closed"))
	{
		if($("#container"+$(el).attr("parent")).html()){
			$("#container"+$(el).attr("parent")).show();	
		}else{
			getNodes($(el).attr("parent"),false);	
		}
		$(el).removeClass("closed");
		$(el).html("-");

	}else{
		$("#container" + $(el).attr("parent")).hide();
		$(el).addClass("closed");
		$(el).html(">");
	}	
}

$( document ).ready(function() {

});