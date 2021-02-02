function tableLoad()
{
	document.getElementById('filterForm').submit();
}

function changeLimit(s) {
	$('#filterLimit').val($(s).val());
	tableLoad();
}

function changePage(p) {
	$('#filterPage').val(p);
	tableLoad();
}
