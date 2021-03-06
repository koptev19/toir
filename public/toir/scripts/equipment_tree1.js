function getNodes(parentId, setposition)
{
	var isContainerEmpty = $("#container" + parentId).html();
	$("#container" + parentId).html("");

	$.ajax({
	    type: "POST",
        url: "/equipments",
        data: {
            ACTION: "getNodes",
			PARENT_ID: parentId
        },
        dataType :'json',
        success: function (data) {
		   	$.each(data, function(index, value) {
			   	let node = (value.countChildren == 0) 
					? "" 
					: "<span class='closed plus' id='sn" + index + "' parent='" + index + "'>></span>";
					   
			   	$("#container" + parentId).append("<div class='d-flex item py-1' el='" + index + "' id='n" + index + "'><div class='node d-flex'>" + node + "</div><div class='d-flex node-name' id='name" + index + "'>" + value.name + "</div></div><div el='" + index + "' id='container" + index + "' class='content ml-2'></div>");
			    $("#sn" + index).click(function(){
					clickOnNode(this);
				});		
				$("#name" + index).click(function(){
					clickOnItem(this);
				});		
			});
			if (setposition) {
				if (!isContainerEmpty) {
					$("#name" + parentId).before("<span class='closed plus' id='sn" + parentId + "' parent='" + parentId + "'>-</span>");
							
					$("#sn" + parentId).click(function() {
						clickOnNode(this);
					});		
				}
				if($("#sn"+parentId).hasClass("closed")) {
					clickOnNode($("#sn" + parentId));
				}
				clickOnItem($("#name" + setposition));
			}
		}
    });    
}

function showNode(id){
	$("#main").html("");
	var path="";
	var n=id;
	var i=0;
	while($("#n"+n).parent().attr("el")!=0&&i<10){
	   n=$("#n"+n).parent().attr("el");
	   path=" > "+$("#name"+n).html()+path;
	   i++;
	}
	$.ajax({
	   type: "POST",
	   url: "/equipments",
	   data: {
		   ACTION: "show",
		   ID: id
	   },
	   dataType :'html',
	   success: function (data) {
		   $("#main").html(data);		   
		 }
	});
}

function createNode(parentId){
	$("#main").html("");
	$.ajax({
	   type: "POST",
	   url: "/equipments",
	   data: {
		   ACTION: "create",
		   PARENT_ID: parentId
	   },
	   dataType :'html',
	   success: function (data) {
		   $("#main").html(data);
		 }
	});
}

function storeNode(el){
	var data = $(el).parent().parent().serializeArray();
	$.ajax({
    	type: "POST",
        url: "/equipments",
        data: {
			ACTION: "store",
			DATA: data 
        },
        dataType :'json',
        success: function (data) {
			if(data.error){
				$(el).parent().find("div.error").html(data.error).show();
			} else {
				getNodes(data.parentId, data.id);
			}
		}
    });
    
}

function editNode(id){
	$("#main").html("");
	$.ajax({
	   type: "POST",
	   url: "/equipments",
	   data: {
		   ACTION: "edit",
		   ID: id,
	   },
	   dataType :'html',
	   success: function (data) {
		   $("#main").html(data);
		 }
	});
}

function updateNode(el){
	var data = $(el).parent().parent().serializeArray();
	$.ajax({
    	type: "POST",
        url: "/equipments",
        data: {
        	ACTION: "update",
			DATA: data 
        },
        dataType :'json',
        success: function (data) {
			if(data.error){
				$(el).parent().find("div.error").html(data.error).show();
			}else{
				showNode(data.id);
				$("#name" + data.id).html(data.name);
			}
		}
    });    
}

function clickOnItem(el){
	$(".item").removeClass("selected");
	$(el).parent().addClass("selected");
	showNode($(el).parent().attr("el"));
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
	getNodes(0, false);
	
	var p = $(".pan1");
    var d = $("#main");
    var r = $("#resize");    
    var curr_width = p.width()
    var unlock = false;   
    $(document).mousemove(function(e) {
          var change = curr_width + (e.clientX - curr_width-80);
       if(unlock) {
                if(change > 199 && change < 699) {
                    p.css("width", change);
                    d.css("margin-left", change);
                }
                else {
                    //p.css("width", 200);
                    //d.css("margin-left", 200);
            }
        }
    });     
    r.mousedown(function(e) {
        curr_width = p.width();
        unlock = true;
        r.css("background-color", "black");
    });
    $(document).mousedown(function(e) {
        if(unlock) {
          e.preventDefault();
        }
    });
    $(document).mouseup(function(e) {
        unlock = false;
        $("#debug").text("");
        r.css("background-color", "grey");
    });

});