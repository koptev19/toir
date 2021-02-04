<html>
<head>
<title>План работ на день профилактики <?php echo $this->date; ?></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body>
<h1 class="text-center">План работ на день профилактики <?php echo d($this->date);?>
<br>

<?php foreach($groupBy as $group){?>


<?php if (!count($operations[$group->ID])){
	continue ;
}else{?>
<h3 class="text-center"><?php echo $group->NAME ?></h3>
<table class="table table-bordered table-sm" id='table3'>
    <thead>
        <tr class='text-center'>
            <th>№</th>
            <th><?php echo $order == "WORKSHOP_ID" ? "Цех" :"Служба" ?></th>
			<th>Наименование оборудования</th>
            <th>Название регламентной операции</th>
            <th>ВИД ТО</th>
            <th>Время проведения</th>
            <th>Примечание</th>
            <th>Тип операции</th>
            <th>Периодичность в днях</th>
            <th>Ответственный исполнитель</th>
            <th>Планируемая Дата выполнения</th>
            <th>Дней до срока выполнения</th>
            <th>Результат</th>
            <th>Последняя дата выполнения</th>
          </tr>
    </thead>
    <tbody>
    <?php foreach($operations[$group->ID] as $operation) {  
        $plan = $operation->plan();
        ?>
        <tr id='operation-<?php echo $operation->ID; ?>'>
            <td><?php echo $operation->ID; ?></td>
			<td>
			<?php echo $order == "WORKSHOP_ID" ? $operation->workshop()->NAME : $operation->service()->NAME; ?>
			</td>
            <td><?php echo $operation->equipment()->path(); ?></td>
            <td><?php echo $operation->NAME; ?> (<?php echo $operation->PLAN_ID ? "Плановая" : "Внеплановая"; ?>)</td>
            <td><?php echo $plan ? Plan::getVerbalTypeTo($plan->TYPE_TO) : ''; ?></td>
            <td><?php echo $operation->WORK_TIME; ?></td>
            <td><?php echo $operation->COMMENT; ?></td>
            <td><?php echo Operation::getVerbalType($operation->TYPE_OPERATION); ?></td>
            <td class='text-center'><?php echo $plan->PERIODICITY; ?></td>
            <td class='text-center'><?php echo $operation->OWNER; ?></td>
            <td class='text-center'><?php echo d($operation->PLANNED_DATE); ?></td>
            <td class='text-center'><?php echo $operation->difference; ?></td>
            <td class='text-center'>
                <?php if($operation->TASK_RESULT == 'N') { ?>
                        <?php echo $operation->COMMENT_NO_RESULT; ?>
                <?php } elseif($operation->TASK_RESULT == 'Y') { ?>
                    <div class='text-success'>Выполнено</div>
                <?php } ?>
            </td>
            <td class='text-center'><?php echo $operation->LAST_DATE_FROM_CHECKLIST; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?}}?>

<script>
self.print();
</script>
</body>
</html>