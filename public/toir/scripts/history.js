function histiryLoad()
{
	document.getElementById('filterForm').submit();
}

function changeLine(s, isFilterToggle = false) {
	$.ajax({
	    type: "POST",
	    url: "history.php",
	    data: {
            workshop: historyWorkshop,
            getMechanismes: 1,
			lines: $(s).val(),
		},
		dataType :'json',
	    success: function ( data ) {
			let html = "";
            for (i in data) {
				html += '<optgroup label="' + i + '">'
	            for (j in data[i]) {
					let selected = filter_mechanism.indexOf(j) != -1 ? "selected" : "";
					html += '<option value="' + j + '" ' + selected + '>' + data[i][j] + '</option>';
				}
				html += '</optgroup>';
            }
			$('#MECHANISM').html(html);
			$('#MECHANISM').select2();
			changeMechanism($('#MECHANISM')[0], isFilterToggle);
        }
	 });
}

function changeMechanism(s, isFilterToggle = false) {
	$.ajax({
	    type: "POST",
	    url: "history.php",
	    data: {
            workshop: historyWorkshop,
            getNodes: 1,
			mechanismes: $(s).val(),
		},
		dataType :'json',
	    success: function ( data ) {
			let html = "";
            for (i in data) {
				html += '<optgroup label="' + i + '">'
	            for (j in data[i]) {
					let selected = filter_node.indexOf(j) != -1 ? "selected" : "";
					html += '<option value="' + j + '" ' + selected + '>' + data[i][j] + '</option>';
				}
				html += '</optgroup>';
            }
			$('#NODE').html(html);
			$('#NODE').select2();
			if (isFilterToggle) {
				$('.filter').toggle();
			}
        }
	 });
}