function chDate(month, year)
{
	let url = new URL(document.location.href);
    url.searchParams.set('month', month);
    url.searchParams.set('year', year);
    document.location.href = url.toString();
}

function editTable1()
{
	$('.table1 tbody input').removeClass('d-none');
	$('#table1Submit').removeClass('d-none');
	$('#table1Cancel').removeClass('d-none');
	$('#table1Edit').addClass('d-none');
}

function cancelEditTable1()
{
	$('.table1 tbody input').addClass('d-none');
	$('#table1Submit').addClass('d-none');
	$('#table1Cancel').addClass('d-none');
	$('#table1Edit').removeClass('d-none');
}

function toggleTable1_2(el)
{

	if($('#table1_2 tr.table-row').css('display') == 'none'){
		$(el).html("скрыть");
		$('#table1_2 tr.table-row').show();
		document.cookie = "table1"+$(el).data("workshop")+"=show";
	}else{
		$(el).html("показать");
		$('#table1_2 tr.table-row').hide();
		document.cookie = "table1"+$(el).data("workshop")+"=hide";
	}
	
}

function editTable2()
{
	$('#table2 input').prop('disabled', false);
	$('#table2 select').prop('disabled', false);
	$('#editTable2').addClass('d-none');
	$('#submitTable2').removeClass('d-none');
}

function editRowTable2(id)
{
	$('#operation-' + id + ' input').prop('disabled', false);
	$('#operation-' + id + ' select').prop('disabled', false);
	$('#operation-' + id + ' .links').addClass('d-none');
	$('#operation-' + id + ' .save').removeClass('d-none');
}

function toggleTable2()
{
	if($("#table2").css('display') == 'none'){
		showTable2()
	}else{
		hideTable2()
	}
}

function showTable2()
{
	$('#link-toggle-table2').html("скрыть");
	$("#table2").show();
	setTable2Cookie($('#link-toggle-table2').data("workshop"), 'show')
	}

function hideTable2()
{
	$('#link-toggle-table2').html("показать");
	$("#table2").hide();
	setTable2Cookie($('#link-toggle-table2').data("workshop"), 'hide')
}

function setTable2Cookie(workshop, value)
{
	document.cookie = "table2" + workshop + "=" + value;
}

function getTable3(workshop, date)
{
	$.ajax({
	    type: "POST",
	    url: "get_table3.php",
	    data: {
			workshop: workshop,
			date: date,
			filter_mechanism_id: filter.mechanism,
			filter_name: filter.name,
		},
	    success: function ( data ) {
			$('#table3').html(data);
	    }
	 });
}

function editRowTable3(id)
{
	$('#operation-' + id + ' input').prop('disabled', false);
	$('#operation-' + id + ' select').prop('disabled', false);
	$('#operation-' + id + ' .links').addClass('d-none');
	$('#operation-' + id + ' .save').removeClass('d-none');
}

function editTable3()
{
	$('#table3 input').prop('disabled', false);
	$('#table3 select').prop('disabled', false);
	$('#table3Edit').addClass('d-none');
	$('#table3Submit').removeClass('d-none');
	$('#table3Cancel').removeClass('d-none');
}

function cancelEditTable3()
{
	$('#table3 input').prop('disabled', true);
	$('#table3 select').prop('disabled', true);
	$('#table3Edit').removeClass('d-none');
	$('#table3Submit').addClass('d-none');
	$('#table3Cancel').addClass('d-none');
}

function printTable3(date, workshop)
{
	$('#print-modal').modal("show");
	$("#print-modal").appendTo("body")
	$('#print-form')[0].date.value = date;
	$('#print-form')[0].workshop.value = workshop;
}

function getTable4(workshop, year, month)
{
	$.ajax({
	    type: "POST",
	    url: "get_table4.php",
	    data: {
			workshop: workshop,
			year: year,
			month: month,
		},
	    success: function ( data ) {
			$('#table4').html(data);
	    }
	 });
}

function showCommentNoResult(operationId)
{
	$('#linkCommentNoResult-' + operationId).addClass('d-none');
	$('#formCommentNoResult-' + operationId).removeClass('d-none');
}

function saveCommentNoResult(operationId)
{
	let form = document.getElementById('form_save_comment_no_result');
	form.operationId.value = operationId;
	form.comment.value = $('#comment_no_result-' + operationId).val();
	form.submit();
}

function filterNoComments(workshop, year, month)
{
	$.ajax({
	    type: "POST",
	    url: "get_table3_no_comments.php",
	    data: {
            workshop:workshop, 
            year: year, 
            month: month
		},
	    success: function ( data ) {
			$('#table3').html(data);
	    }
	 });
}

function filterNotPush(workshop, year, month)
{
	$.ajax({
	    type: "POST",
	    url: "get_table3_not_push.php",
	    data: {
            workshop:workshop, 
            year: year, 
            month: month
		},
	    success: function ( data ) {
			$('#table3').html(data);
	    }
	 });
}

function filterNotDone(workshop)
{
	$.ajax({
	    type: "POST",
	    url: "get_table2_not_done.php",
	    data: {
            workshop:workshop, 
			filter: filter,
		},
	    success: function ( data ) {
			$('#table2-link1').removeClass('h4');
			$('#table2-link1').removeClass('text-dark');
			$('#table2-link2').removeClass('h4');
			$('#table2-link2').removeClass('text-dark');
			$('#table2-link3').addClass('h4');
			$('#table2-link3').addClass('text-dark');
			$('#table2').html(data);
			showTable2()
	    }
	 });
}

function showHistories(crashId) 
{
	$("#crashModalContent").html("Загружаю операции...");
    $('#crashModal h5').html('Операции аварии ' + crashId);
	$("#crashModal").appendTo("body")
	$("#crashModal").modal("show");

	$.ajax({
    type: "POST",
    url: "crash_edit.php",
    data: {
      histories: 1,
			crash: crashId,
		},
	    success: function ( data ) {
        $("#crashModalContent").html(data);
	    }
	});
}
