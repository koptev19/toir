<div class="btn-toolbar justify-content-end py-2">
    <a href="analitics.php" class="btn btn-outline-primary me-3"><i class="fas fa-chart-line"></i> Аналитика</a>
    <a href="/equipments" class="btn btn-outline-primary me-3"><i class="fas fa-hammer"></i> Оборудование</a>
    <div class="btn-group" role="group">
        <button id="btnGroupDrop1" type="button" class="btn btn-outline-primary me-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-align-justify"></i> Журналы
        </button>
        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
            <li><a class="dropdown-item" href="history.php?workshop=<?php echo $this->workshop->ID; ?>" target="_blank">Журнал учета работ</a></li>
            <li><a class="dropdown-item" href="log_receiving.php" target="_blank">Журнал приемки оборудования</a></li>
            <li><a class="dropdown-item" href="service_request.php" target="_blank">Журнал заявок на ремонт</a></li>
            <li><a class="dropdown-item" href="crashes.php?workshop=<?php echo $this->workshop->ID; ?>" target="_blank">Журнал аварий</a></li>
            <li><a class="dropdown-item" href="writeoffs.php?workshop=<?php echo $this->workshop->ID; ?>" target="_blank">Журнал списания ТМЦ</a></li>
            <li><a class="dropdown-item" href="delayed_writeoffs.php" target="_blank">Журнал "Отложенных списаний ТМЦ"</a></li>
            <li><a class="dropdown-item" href="work_planned_log.php" target="_blank">Журнал плановых операций</a></li>
            <li><a class="dropdown-item" href="/downtimes" target="_blank">Журнал простоев</a></li>
        </ul>
    </div>
    <a href="#" onclick="addOperationGroup()" class="btn btn-outline-primary me-3"><i class="fas fa-plus"></i> Добавить операции</a>

    <a href="/profile/logout" class="btn"><i class="fas fa-sign-out-alt"></i></a>
</div>

<?php foreach($errors as $error) { ?>
    <div class='alert alert-danger mb-4 text-center'><?php echo $error; ?></div>
<?php } ?>
<style>
.custom-control-label::before {
    background-color:#ffffff;
    border:1px solid #dee2e6;
}
</style>

<script src="scripts/index.js"></script>

<script>
function addOperationGroup()
{
    <?php if(count(UserToir::current()->availableServices) > 1) { ?>
    	$('#services-add-operation-group').modal("show");
	    $("#services-add-operation-group").appendTo("body")
    <?php } else { 
        $service = reset(UserToir::current()->availableServices); ?>
        window.open('add_operation_group.php?service=<?php echo $service->ID; ?>&source=<?php echo Operation::SOURCE_GROUP_INDEX; ?>');
    <?php } ?>
}

function closeOperationGroup()
{
    $('#services-add-operation-group').find('form').submit();
    $('#services-add-operation-group').modal("hide");
}

</script>

<div class="modal fade" tabindex="-1" id='services-add-operation-group'>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="add_operation_group.php" target="_blank">
            <input type="hidden" name="source" value="<?php echo Operation::SOURCE_GROUP_INDEX; ?>">
            <div class='modal-body'>
		        <?php foreach(UserToir::current()->availableServices as $service) { ?>
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="add-operation-group-service-<?php echo $service->ID; ?>" name="service" value="<?php echo $service->ID; ?>">
                    <label class="custom-control-label" for="add-operation-group-service-<?php echo $service->ID; ?>"><?php echo $service->NAME; ?></label>
                </div>
		        <?php } ?>
			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="closeOperationGroup();">Добавить</button>
			</div>	
		</div>
            </form>
	</div>
</div>
