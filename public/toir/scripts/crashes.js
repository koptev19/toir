function addHistory(crashId)
{
	$.ajax({
        type: "POST",
        url: "crash_edit.php",
        data: {
            select_service_request: 1,
            crash: crashId,
        },
        success: function ( data ) {
            $("#addCrashHistoryModal .modal-body").html(data);
            $("#addCrashHistoryModal").appendTo("body")
            $("#addCrashHistoryModal").modal("show");
            $("#addCrashHistoryModal form").attr('action', 'add_history_group.php');
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

function addOperationGroup(url, crashId)
{
	$.ajax({
        type: "POST",
        url: "crash_edit.php",
        data: {
            select_services: 1,
            crash: crashId,
        },
        success: function ( data ) {
            $("#addCrashHistoryModal .modal-body").html(data);
            $("#addCrashHistoryModal").appendTo("body")
            $("#addCrashHistoryModal").modal("show");
            $("#addCrashHistoryModal form").attr('action', url);
        }
    });
}

function showOperations(crashId) 
{
	$("#crashModalContent").html("Загружаю операции...");
  $('#crashModal h5').html('Операции аварии ' + crashId);
	$("#crashModal").appendTo("body")
	$("#crashModal").modal("show");

	$.ajax({
    type: "POST",
    url: "crash_edit.php",
    data: {
        operations: 1,
        crash : crashId,
    },
    dataType :'html',
    success: function ( data ) {
      $("#crashModalContent").html(data);
    }
	 });
}

function editDescription(crashId)
{
	$("#crashModalContent").html("Подождите ...");
  $('#crashModal h5').html('Описание аварии ' + crashId);
  $("#crashModal").appendTo("body")
	$("#crashModal").modal("show");
	$.ajax({
        type: "GET",
        url: "crash_edit.php",
        data: {
            edit_description: 1,
            crash : crashId,
        },
        dataType :'html',
        success: function (data) {
            $("#crashModalContent").html(data);
        }
    });    
}

function editDecision(crashId)
{
  $("#crashModalContent").html("Подождите ...");
  $('#crashModal h5').html('Решение аварии ' + crashId);
  $("#crashModal").appendTo("body")
	$("#crashModal").modal("show");
	$.ajax({
        type: "GET",
        url: "crash_edit.php",
        data: {
            edit_decision: 1,
            crash : crashId,
        },
        dataType :'html',
        success: function (data) {
            $("#crashModalContent").html(data);
        }
    });    
}

function addFiles(crashId)
{
    $('#addFilesModal h5').html('Добавление файлов аварии ' + crashId);
	$("#addFilesModal").modal("show");
  $("#addFilesModal").appendTo("body")
	$("#addFilesCrash").val(crashId);
	$("#addFilesAction").attr('name', 'save_files');
}

function addDecisionFiles(crashId)
{
    $('#addFilesModal h5').html('Добавление файлов к решению аварии ' + crashId);
	$("#addFilesModal").modal("show");
  $("#addFilesModal").appendTo("body")
	$("#addFilesCrash").val(crashId);
	$("#addFilesAction").attr('name', 'save_decision_files');
}

function delDocumentFile(crashId, file)
{
  if(!confirm('Удалить файл?')) {
    return;
  }
	$.ajax({
        type: "GET",
        url: "crash_edit.php",
        data: {
            delete_document_file: file,
            crash : crashId,
        },
        dataType : 'json',
        success: function (data) {
            $('#file-' + file).remove();
        }
    });    
}

function delDecisionFile(crashId, file)
{
  if(!confirm('Удалить файл?')) {
    return;
  }
	$.ajax({
        type: "GET",
        url: "crash_edit.php",
        data: {
            delete_decision_file: file,
            crash : crashId,
        },
        dataType : 'json',
        success: function (data) {
            $('#file-' + file).remove();
        }
    });    
}