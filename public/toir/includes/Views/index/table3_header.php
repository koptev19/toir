<div class="modal" tabindex="-1" id='service-select-modal'>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
				<div id='service-select-hidden'></div>
				<table class="table table-bordered table-hover text-center" id="service-select-list">
					<thead></thead>
					<tbody></tbody>
                </table>
			</div>
                       
        </div>
    </div>
</div>

<script>
var s="";
function selectServiceOpen(url, date, mode){
	$.ajax({
	    type: "POST",
        url: "ajax.php",
        data: {
            action: "getDateProcess",
			date: date,
            mode: mode
        },
        dataType :'json',
        success: function (data) {
    		$("#service-select-list thead").html("");
    		$("#service-select-list tbody").html("");
            $("#service-select-form").attr("action", url);
            if(Object.keys(data.services).length > 1) {
                var html = "";
				html += '<tr><td> Цех / Сервис';
				$.each(data.services, function(index, service) {
			      html += '<td>';
				  html += '<a target= "_blank" href="'+url+'?service='+service.id+'&date='+date+'">'+service.name+'</a>';
				   
				});
				$("#service-select-list thead").append(html);
				html ="";
				$.each(data.workshops, function(workshopId, workshop) {
					var sh=0;
                     $.each(data.services, function(serviceId, services) {
						if(sh==0){
							html += '<tr>';
							html += '<td>'+workshop.name + '</td>';
							sh=1;
						}	
						if (data.table[workshopId][serviceId] === undefined){
							html += '<td></td>';
						}else{
							html += '<td>'+ data.table[workshopId][serviceId] + '</td>';
						}
									
				  })	
				});
				$("#service-select-list tbody").append(html);
                $('#service-select-modal').modal("show");
                $("#service-select-modal").appendTo("body")
            } else {
               window.open(url+"?date="+date+"&service="+Object.keys(data.services)[0]);
            }
		}
	});   	
	
	return false;
}

function serviceSelectSubmit()
{
    $("#service-select-form").submit();
    $('#service-select-modal').modal("hide");
}


</script>