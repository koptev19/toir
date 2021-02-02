function getMechanismes(lineId)
{
	$('#MECHANISM').empty();

    $.ajax({
		type: "POST",
		url: "ajax.php",
	    data: {
			action: 'getMechanismes',
			line: lineId,
		},
		dataType :'json',
	    success: function ( data ) {
			for (i in data) {
				$('#MECHANISM').append("<option value='" + i + "'>" + data[i] + "</option>");
			}
			$('#MECHANISM').select2();
	    }
	 });
}

function getNodes(mechanismId)
{
	$('#NODE').empty();

    $.ajax({
		type: "POST",
		url: "ajax.php",
	    data: {
			action: 'getNodes',
			mechanism: mechanismId,
		},
		dataType :'json',
	    success: function ( data ) {
			for (i in data) {
				$('#NODE').append("<option value='" + i + "'>" + data[i] + "</option>");
			}
			$('#NODE').select2();
	    }
	 });
}