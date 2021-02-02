<h1 class='text-center'>Шаг 2 - прижать операции к датам остановки</h1>

<?php 
if($_SESSION['add_plan_errors'] && is_array($_SESSION['add_plan_errors']) && count($_SESSION['add_plan_errors'])){
    foreach ($_SESSION['add_plan_errors'] as $error){
        echo "<div class='alert alert-danger mt-4' role='alert'>" . $error . "</div>";
    }
}

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
                    <?php if(in_array($date2, $dates)){
                        if(!isset($stops[$date2])){ ?>
                            <div class="dropdown">
                                <a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="link<?php echo date("dm", mktime(0, 0, 0, $month, $i, $year)); ?>">1</a>
                                <ul class="dropdown-menu" aria-labelledby="link<?php echo date("dm", mktime(0, 0, 0, $month, $i, $year)); ?>">
                                    <li><a class="dropdown-item" href="#" onClick="createStop(<?php echo $line->ID; ?>, '<?php echo $date; ?>'); return false">Создать день остановки</a></li>
                                    <li><a class="dropdown-item" href="?workshop=<?php echo $this->workshop->ID; ?>&pushToLeft=<?php echo $date; ?>">Прижать влево</a></li>
                                    <li><a class="dropdown-item" href="?workshop=<?php echo $this->workshop->ID; ?>&pushToRight=<?php echo $date; ?>">Прижать вправо</a></li>
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

<div class="text-center mt-5">
    <a class="btn btn-primary" href="?step=2&save=1&workshop=<?php echo $this->workshop->ID; ?>" role="button">Прижать операции влево и перейти на шаг 3</a>
    <a class="btn btn-outline-secondary ml-4" href="<?php echo $this->getUrlStep1(); ?>" role="button">Удалить операцию и создать заново</a>
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