<?php
$this->view('_header', ['title' => "План работ на день профилактики " . $this->date]);?>

<div class="modal" tabindex="-1" id='print-modal'>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
				<a target="_blank" href="?date=<?php echo $this->date?>&workshop=<?php echo $_REQUEST['workshop'] ?>&service=<?php echo $_REQUEST['service'] ?>&groupBy=<?php echo $_REQUEST['groupBy'] ?>&action=printTable" onclick="">План работ на день профилактики
				</a><br>
				<a target="_blank" href="?date=<?php echo $this->date?>&workshop=<?php echo $_REQUEST['workshop'] ?>&service=<?php echo $_REQUEST['service'] ?>&groupBy=<?php echo $_REQUEST['groupBy'] ?>&action=WorkersRoutePrint" onclick="">
				Маршрутный лист сотрудников (все цеха, все службы)
				</a><br>
				<a target="_blank" href="?date=<?php echo $this->date?>&workshop=<?php echo $_REQUEST['workshop'] ?>&service=<?php echo $_REQUEST['service'] ?>&groupBy=<?php echo $_REQUEST['groupBy'] ?>&action=WorkersRoutePrint&filtred=1" onclick="">
				Маршрутный лист сотрудников (отфильтрованный)
				</a>
                <div class='row my-4'>
                    <div class='col-6'>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    </div>
                </div>
			</div>
        </div>
    </div>
</div>

<script>
	function printTable(){
		$('#print-modal').modal('show');
	    $("#print-modal").appendTo("body")
	}
</script>
