<div class='mb-1 row'>
    <div class='col-1'><a href="#" onclick="$('.filter').toggle(); return false;">Фильтр</a></div>
    <div class='col-2' id="clean-filter"><a href="?workshop=<?php echo $this->workshop->ID; ?>">Сбросить фильтр</a></div>
</div>
<form method="get" action="" id='filterForm'>
    <input type=hidden name='workshop' value='<?php echo $this->workshop->ID; ?>'>
	<div class='filter mt-4 mb-4 col-8' style="display:none;">

        <div class="row mb-3">
            <div class='col-3'>Дата</div>
            <div class="col-4">
                <input type="date" name="filter[PLANNED_DATE_FROM]" value="<?php echo $this->filter['PLANNED_DATE_FROM']?>" class="form-control" onchange="this.form.submit()">
            </div>
            <div class="col-1 pt-2 text-center">
                -
            </div>
            <div class="col-4">
                <input type="date" name="filter[PLANNED_DATE_TO]" value="<?php echo $this->filter['PLANNED_DATE_TO']?>" class="form-control" onchange="this.form.submit()">
            </div>
        </div>

        <div class="row mb-3">
        <div class='col-3'><label class="col-form-label">Мат. ответственное лицо:</label></div>
        <div class="col-9">
            <select name="filter[USER_ID]" class="form-control form-select" onchange="this.form.submit()">
				<option value="">	
				<?php foreach(UserService::getList() as $user) { ?>
                    <option <?php echo($user->id==$this->filter['USER_ID'])?"selected":"" ?> value="<?php echo $user->id?>" ><?php echo $user->fullname; ?></option>
                <?php } ?>
            </select>            
        </div>
	    </div>

		
		<div class="row mb-3">
            <div class='col-3'>Запчасть</div>
            <div class="col-9">
                <input type="text" name="filter[%NAME]" class="form-control" value='<?php echo $this->filter['%NAME']?>' onchange="this.form.submit()">
            </div>
        </div>

		<div class="row mb-3">
        <div class='col-3'><label class="col-form-label">Склад</label></div>
        <div class="col-9">
			<select name="filter[STORE]" class="form-control form-select" onchange="this.form.submit()">
					<option value="">	
				    <option <?php echo($this->filter['STORE']=="Склад технических материалов")?"selected":"" ?> value="Склад технических материалов">Склад технических материалов</option>
                    <option <?php echo($this->filter['STORE']=="ЦЛШ Романов")?"selected":"" ?> value="ЦЛШ Романов">ЦЛШ Романов</option>
					<option <?php echo($this->filter['STORE']=="ЦКФ Князьков")?"selected":"" ?> value="ЦКФ Князьков">ЦКФ Князьков</option>
					<option <?php echo($this->filter['STORE']=="Биржа сырья Борисов")?"selected":"" ?> value="Биржа сырья Борисов">Биржа сырья Борисов</option>
			</select>            
        </div>
	    </div>

        <div class="row mb-3">
            <div class='col-3'>Операция</div>
            <div class="col-9">
                <input type="text" name="" class="form-control" value='' onchange="this.form.submit()">
            </div>
        </div>

        <div class="row mb-3">
            <div class='col-3'>Оборудование</div>
            <div class="col-9">
                <?php
                $this->view('components/select_equipment', [
                    'fieldName' => 'filter[EQUIPMENT_ID]',
                    'equipment' => $this->filter['EQUIPMENT_ID'] ?? $this->workshop->ID
                ]);
                ?>
            </div>
        </div>
    </div>
<input type="hidden" name="limit" value="<?php echo $limit; ?>" id='filterLimit'>
<input type="hidden" name="page" value="1" id='filterPage'>
</form>

<script>
$(document).ready(function() {
    $('#equipment').on('change', function() {
        if($(this).val() && $(this).val() != '<?php echo $filter['EQUIPMENT_ID'];?>' && $(this).val() != '<?php echo $this->workshop->ID;?>') {
            this.form.submit();
        }
    });
})
</script>