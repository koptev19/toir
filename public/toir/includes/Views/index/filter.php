<form method="get" action="index.php">
<input type="hidden" name="month" value="<?php echo $this->month; ?>">
<input type="hidden" name="year" value="<?php echo $this->year; ?>">
<input type="hidden" name="table2" value="<?php echo $this->table2; ?>">

<div class='row mb-2'>
    <div class='col-md-4'>
        <select id='filterWorkshop' class='form-control form-select' onchange="this.form.submit();" name='workshop'>
            <?php foreach($allWorkshops as $w) { ?>
                <option value="<?php echo $w->ID; ?>" <?php if($w->ID == $this->workshop->ID) echo "selected";  ?>><?php echo $w->NAME; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class='col-md-4'>
        <?php if(count($services) > 1) { ?>
            <select name="SERVICE_ID" class='form-control form-select' onchange="this.form.submit();">
                <option value=''>Выбрать все доступные службы</option>
                <?php foreach($services as $service) { ?>
                    <option value='<?php echo $service->ID; ?>' <?php if($this->filter['SERVICE_ID'] == $service->ID) echo "selected"; ?>><?php echo $service->NAME; ?></option>
                <?php } ?>
            </select>
        <?php } else { ?>
            <input type="text" value="<?php echo reset($services)->NAME; ?>" class="form-control" readonly>
            <input type="hidden" name='SERVICE_ID' value="<?php echo key($services); ?>">
        <?php } ?>
    </div>
    <div class='col-md-4'>
        <input id='#filterName' class='form-control' placeholder="Название операции" name='filter_name' value="<?php echo htmlspecialchars($this->filter['name']); ?>">
    </div>
</div>
<div class='row mb-3'>
    <div class='col-md-4'>
        <select id='filterLine' class='form-control form-select' onchange="this.form.submit()" name='filter_line'>
            <option value='0'>Все линии</option>
            <?php foreach($this->workshop->lines as $line) { ?>
                <option value="<?php echo $line->ID; ?>" <?php if($line->ID == $this->filter['line']) echo "selected";  ?>><?php echo $line->NAME; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class='col-md-4'>
        <select id='filterMechanism' class='form-control form-select' name='filter_mechanism' onchange="this.form.submit()">
            <option value='0'>Все механизмы</option>
            <?php if($this->filter['line']) {
                $line = Line::find((int)$this->filter['line']);
                if($line) {
                    foreach($line->children as $child) {?>
                        <option value="<?php echo $child->ID; ?>" <?php if($child->ID == $this->filter['mechanism']) echo "selected";  ?>><?php echo $child->NAME; ?></option>
                    <?php }
                }
            }?>
        </select>
    </div>
    <div class='col-md-4 text-right'>
        <a href="?month=<?php echo $this->month; ?>&year=<?php echo $this->year; ?>&workshop=<?php echo $this->workshop->ID; ?>&table2=<?php echo $this->table2;?>" class="btn btn-outline-secondary">Сбросить фильтр</a>
    </div>
</div>
</form>

<script>

var filter = <?php echo json_encode($this->filter); ?>;

function filterChangeWorkshop()
{
	$.ajax({
	    type: "POST",
	    url: "ajax.php",
	    data: {
			action: 'getLines',
			workshop: $('#filterWorkshop').val(),
		},
		dataType :'json',
	    success: function ( data ) {
			let html = '';
			for (i in data) {
                let selected = Number(i) == Number(filter['line']) ? "selected" : ""
				html += "<option value='" + i + "' " + selected + ">" + data[i] + "</option>";
			}
			$('#filterLine').empty().html("<option value='0'>Все линии</option>" + html);

            filterChangeLine();
	    }
	 });
}

function filterChangeLine()
{
    if (Number($('#filterLine').val()) > 0) {
        $.ajax({
            type: "POST",
            url: "ajax.php",
            data: {
                action: 'getMechanismes',
                line: $('#filterLine').val(),
            },
            dataType :'json',
            success: function ( data ) {
                let html = '';
                for (i in data) {
                    let selected = Number(i) == Number(filter['mechanism']) ? "selected" : ""
                    html += "<option value='" + i + "' " + selected + ">" + data[i] + "</option>";
                }
                $('#filterMechanism').empty().html("<option value='0'>Все механизмы</option>" + html);
            }
        });
    }
}


$(document).ready(function() {
//    filterChangeWorkshop();
});

</script>