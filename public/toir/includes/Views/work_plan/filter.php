<div class="mb-4">
	<form method="get" action="">
	<input type="hidden" name="date" value="<?php echo $this->date; ?>">


	<div class='row mb-2'>
		<div class='col-md-4'>
			<select id='filterWorkshop' class='form-control form-select' name='workshop' onchange="this.form.submit()">
				<option value=''>Выбрать все доступные цеха</option>
				<?php foreach($allWorkshops as $w) { ?>
					<option value="<?php echo $w->ID; ?>" <?php if($w->ID == $_REQUEST['workshop']) echo "selected";  ?>><?php echo $w->NAME; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class='col-md-4'>
			<?php if(count($services) > 1) { ?>
				<select name="service" class='form-control form-select' onchange="this.form.submit()">
					<option value=''>Выбрать все доступные службы</option>
					<?php foreach($services as $service) { ?>
						<option value='<?php echo $service->ID; ?>' <?php if($_REQUEST['service'] == $service->ID) echo "selected"; ?>><?php echo $service->NAME; ?></option>
					<?php } ?>
				</select>
			<?php } else { ?>
				<input type="text" value="<?php echo reset($services)->NAME; ?>" class="form-control" readonly>
				<input type="hidden" name='SERVICE_ID' value="<?php echo key($services); ?>">
			<?php } ?>
		</div>
		<div class='col-md-4'>
				 <select name="groupBy" class='form-control form-select' onchange="this.form.submit()">
					<option <?php if($_REQUEST['groupBy'] == "WORKSHOP_ID") echo "selected"; ?> value='WORKSHOP_ID'>Группировать по цехам</option>
					<option <?php if($_REQUEST['groupBy'] == "SERVICE_ID") echo "selected"; ?> value='SERVICE_ID'>Группировать по службам</option>
				</select>
			
		</div>	
	 </div>
	</form>
</div>

