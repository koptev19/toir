<html>
<head>
<title>План работ на день профилактики <?php echo $date; ?></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body>
<div class='container'>
<h3 class='text-center mt-4'><?php echo $this->workshop->NAME; ?></h3>
<h3 class='text-center mt-4'>План работ на день профилактики <?php echo $date; ?></h3>
<?php foreach($operations as $serviceId => $serviceOperations) { ?>
    <?php foreach($serviceOperations as $planKey => $planOperations) { ?>
    <h4 class='mt-5'><?php echo $services[$serviceId]->NAME; ?> (<?php echo $planKey ? "Плановые операции" : "Внеплановые операции"; ?>)</h4>
    <table class='table-sm table-bordered'>
            <tr class='text-center'>
                <th>№</th>
                <th>Наименование оборудования</th>
                <th>Наименование операции</th>
                <th>Время проведения</th>
                <th>Исполнители</th>
                <th>Рекомендации</th>
                <th>Примечание</th>
            </tr>
            <?php $n=0; foreach($planOperations as $operation) { $n++; ?>
                <tr>
                    <td><?php echo $n;?></td>
                    <td><?php echo $operation->equipment()->path(); ?></td>
                    <td><?php echo $operation->NAME; ?></td>
                    <td class='text-center'><?php echo $operation->WORK_TIME; ?></td>
                    <td class='text-center'><?php echo $operation->OWNER; ?></td>
                    <td><?php echo $operation->RECOMMENDATION; ?></td>
                    <td><?php echo $operation->COMMENT; ?></td>
                </tr>
            <?php }?>
    </table>
<?php }?>
<?php }?>
</div>

<script>
self.print();
</script>
</body>
</html>