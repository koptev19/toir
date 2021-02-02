<h1 class='text-center'>Шаг 2 - прижать операции к датам остановки</h1>

<?php if($_REQUEST['error_date']) { ?>
<div class='alert alert-danger text-center my-5'>
    <div>Прижатие невозможно! На дату <?php echo $_REQUEST['error_date']; ?> попадает несколько одинаковых операций</div>
</div>
<?php }

$month = (int)date("m"); 
$year = (int)date("Y"); 
$maxI = date('j') < 20 ? 2 : 3;
for($m = 0; $m < $maxI; $m++) { 
    ?>
<h4 class='mt-4 text-center'><?php echo monthName($month); ?> <?php echo $year;?></h4>
<table class='table table-sm table-bordered table1 mt-3' id='table1_1'>
    <thead>
        <tr class='text-center'>
            <th>Линия</th>
            <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $month, 1, $year)); $i++){
            $class = isWeekend($i, $month, $year) ? 'table-danger' : '';
            ?>
            <td class='<?php echo $class; ?>'>
                <?php echo $i; ?>
            </td>
        <?php } ?>
        </tr>
    </thead>


    <tbody>
        <tr class='text-center'>
            <td class='text-left'>
                <?php echo $this->operation->line()->link(); ?>
            </td>
            <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $month, 1, $year)); $i++) {
                $date = date("d.m.Y", mktime(0, 0, 0, $month, $i, $year));
                $date2 = date("Y-m-d", mktime(0, 0, 0, $month, $i, $year));
                $class = isset($stoppedDates[$date2]) ? "table-success" : (isWeekend($i, $month, $year) ? 'table-danger' : '');
                ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <?php if(isset($operations[$date2])){
                        if(!isset($stoppedDates[$date2])){ ?>
                            <div class="dropdown">
                                <a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="link<?php echo date("dm", mktime(0, 0, 0, $month, $i, $year)); ?>">1</a>
                                <ul class="dropdown-menu" aria-labelledby="link<?php echo date("dm", mktime(0, 0, 0, $month, $i, $year)); ?>">
                                    <li><a class="dropdown-item" href="#" onClick="createStop(<?php echo $this->operation->LINE_ID; ?>, '<?php echo $date; ?>'); return false">Создать день остановки</a></li>
                                    <li><a class="dropdown-item" href="?operation_id=<?php echo $this->operation->ID; ?>&pushToLeft=<?php echo $date; ?>">Прижать влево</a></li>
                                    <li><a class="dropdown-item" href="?operation_id=<?php echo $this->operation->ID; ?>&pushToRight=<?php echo $date; ?>">Прижать вправо</a></li>
                                </ul>
                            </div>
                        <?php } else { ?>
                            1
                        <?php } ?>
                    <?php } ?>
                </td>
            <?php } ?>

        </tr>
    </tbody>
</table>
<?php 
$year = $month == 12 ? $year + 1 : $year;
$month = $month == 12 ? 1 : $month + 1;
} ?>

<div class='text-center pt-5' id='button-next'><a class="btn btn-primary" href="?step=3&operation_id=<?php echo $this->operation->ID;?>&go=1" role="button">Прижать и перейти на шаг 3</a></div>

<div class='text-center pt-5' id='button-next'><a class="btn btn-primary" href="?step=1&operation_id=<?php echo $this->operation->ID;?>" role="button">Вернуться к редактированию данных</a></div>

<script>

function createStop(lineId, date)
{
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            action: 'createStop',
            lineId: lineId,
            date: date,
        },
        dataType :'json',
        success: function (data) {
            if (data.error) {
                alert(data.error);
            } else {
                document.location.reload();
            }			
        }
    });
}

</script>