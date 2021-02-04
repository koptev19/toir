<html>
<head>
<title>Журнал списания ТМЦ</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body>
<div class='container'>
<div class="row h5 mt-5">
    <div class="col-6">
        ЗАО "Плайтера"
    </div>
    <div class="col-6 text-right" style="line-height:2rem;">
        Утверждаю:<br>
        Зам. ген. дир. по безопасности<br>
        ________________________/_______________________<br>
    </div>
</div>

<div class="text-center my-4 h5">
    Ведомость списания запасных частей<br>
    на ремонт оборудования<br>
    с <?php echo d($this->filter['PLANNED_DATE_FROM']); ?>  по <?php echo d($this->filter['PLANNED_DATE_TO']); ?>
</div>

<table class="table table-bordered table-sm" id='table3'>
    <thead>
        <tr class='text-center'>
            <th>№</th>
            <th>Наименование</th>
            <th>Ед. измерения</th>
            <th>Количество</th>
            <th>Причина списания</th>
            <th>Оборудование</th>
        </tr>
    </thead>
    <tbody>
		<?php 
        $num = 0;
        foreach ($writeOffs as $writeoff){ 
            $num++;
            ?>
			<tr class=''>
                <td class="text-center"><?php echo $num; ?></td>
                <td><?php echo $writeoff->NAME?></td>
                <td class="text-center"><?php echo $writeoff->UNIT?></td>
                <td class="text-center"><?php echo $writeoff->QUANTITY?></td>
                <td><?php echo $operations[$writeoff->OPERATION_ID]->NAME ?></td>
                <td><?php echo $equipments[$writeoff->EQUIPMENT_ID]->path(false); ?></td>
			</tr>
		<?php }?>
    </tbody>
</table>


<div class="row mb-2 pt-4">
    <div class="col-3 font-weight-bold">Механик цеха:</div>
    <div class="col-9">____________________________ / ____________________________</div>
</div>
<div class="row mb-2">
    <div class="col-3 font-weight-bold">Председатель комиссии: </div>
    <div class="col-9">Гл. инженер ____________________________ / ____________________________</div>
</div>
<div class="row mb-2">
    <div class="col-3 font-weight-bold"> Члены комиссии: </div>
    <div class="col-9">Гл. механик: ____________________________ / ____________________________</div>
</div>
<div class="row">
    <div class="col-3"></div>
    <div class="col-9">Инженер по комплектации: ____________________________ / ____________________________</div>
</div>


</div>

<script>
self.print();
</script>
</body>
</html>