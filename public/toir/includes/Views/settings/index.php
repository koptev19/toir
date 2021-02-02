<h5>Настройки</h5>
<form action="" method="post">
<input type="hidden" name="ACTION" value="update">
<input type="hidden" name="ID" value="<?php echo $user->ID; ?>">
    <div class="row mb-3 mt-3">
        <div class='col-4 col-md-3'><label class="col-form-label">Дата планирования "График ТОиР" :</label></div>
        <div class='col-8 col-md-9'><input name='plan_month_day' type="number" min ="1" max ="31" step="1" class="form-control" value="<?php echo $planMonthDay->VALUE; ?>" ></div>
    </div>
    <div class='mt-4'>
        <input type="submit" value="Сохранить" class='btn btn-primary'>
	</div>
</form>




