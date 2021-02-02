<h1 class='text-center'>Шаг 3 - Итог</h1>
<?php
$month = (int)date("m"); 
$year = (int)date("Y"); 
$maxI = date('j') < 20 ? 2 : 3;
for($m = 0; $m < $maxI; $m++)   { 
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
                $date = date("Y-m-d", mktime(0, 0, 0, $month, $i, $year));
                $class = isset($stoppedDates[$date]) ? "table-success" : (isWeekend($i, $month, $year) ? 'table-danger' : '');
                $c = isset($operations[$date]) ? 1 : "";
                ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <?php echo $c ?>
                </td>
            <?php } ?>

        </tr>
    </tbody>
</table>
<?php 
$year = $month == 12 ? $year + 1 : $year;
$month = $month == 12 ? 1 : $month + 1;
             } ?>

<div class='text-center pt-5' id='button-next'><a class="btn btn-primary" href="#" role="button" onclick="window.close(); return false;">Завершить</a></div>

