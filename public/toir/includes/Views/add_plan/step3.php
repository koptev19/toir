<h1 class='text-center'>Шаг 3 - прижать операции к датам остановки</h1>

<div class="alert alert-warning mt-4 text-center" role="alert">
  Внимание!<br>
  Операция еще не создана. Для завершения создания операции нажмите на одну из кнопок ниже.
</div>

<?php 
	  
$month = (int)date("m", strtotime($plan->PLANNED_DATE)); 
$year = (int)date("Y", strtotime($plan->PLANNED_DATE)); 
for($m = 0; $m < $this->maxMonth(); $m++) { 
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
                    <?php echo $line->NAME; ?>
                </td>
                <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $month, 1, $year)); $i++) {
                    $date = date("d.m.Y", mktime(0, 0, 0, $month, $i, $year));
                    $date2 = date("Y-m-d", mktime(0, 0, 0, $month, $i, $year));
                    $class = isset($stops[$date2]) ? "table-success" : (isWeekend($i, $month, $year) ? 'table-danger' : '');
                    ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <?php if(in_array($date2, $dates)){ ?>
                            1
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

<div class="text-center mt-5">
    <a class="btn btn-primary" href="?workshop=<?php echo $this->workshop->ID; ?>&step=3&save=1" role="button">Добавить операцию</a>
    <a class="btn btn-outline-primary mx-4" href="?workshop=<?php echo $this->workshop->ID; ?>&step=3&save=1&next=1" role="button">Добавить операцию и создать еще</a>
    <a class="btn btn-outline-secondary" href="<?php echo $this->getUrlStep1(); ?>" role="button">Удалить операцию и создать заново</a>
</div>


<script>

function createStop(lineId, date)
{
    $.ajax({
        type: "POST",
        url: "ajax.php",
        data: {
            action: 'createStop',
            lineId: lineId,
            date: date
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