<h1 class='text-center'>Шаг 2</h1>

<?php 
	  
$month = (int)date("m"); 
$year = (int)date("Y"); 
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
                    $class = isset($stops[$date]) ? "table-success" : (isWeekend($i, $month, $year) ? 'table-danger' : '');
                    ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <?php if($date == $operation->PLANNED_DATE){ ?>                        
                            1
                    <?php } ?>
                </td>
            <?php } ?>
            </tr>
        </tbody>
    </table>

<div class='text-center pt-5'>
    <a class="btn btn-primary" href="#" role="button" onclick="closeAdd(); return false;">Добавить операцию</a>
    <a class="btn btn-outline-primary mx-4" href="<?php echo $this->getUrlStep1(); ?>" role="button">Добавить операцию и создать еще</a>
    <a class="btn btn-outline-secondary" href="?workshop=<?php echo $this->workshop->ID; ?>&delete=<?php echo $operation->ID; ?>" role="button">Отмена</a>
</div>

<script>

function closeAdd()
{
    opener.location.href = opener.location.href; 
    self.close();
}

</script>