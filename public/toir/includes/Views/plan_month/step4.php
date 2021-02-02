<h5 class='text-center mt-5'>Шаг 4 - итог</h5>

<table class='table table-sm table-bordered table1 mt-5' id='table1_1'>
    <thead>
        <tr class='text-center'>
            <th>Линия</th>
            <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $this->date->month, 1, $this->date->year)); $i++){
            $class = isWeekend($i, $this->date->month, $this->date->year) ? 'table-danger' : '';
            ?>
            <td class='<?php echo $class; ?>'>
                <?php echo $i; ?>
            </td>
        <?php } ?>
        </tr>
    </thead>


    <tbody>
    <?php foreach($lines as $line) { ?>
        <tr class='text-center'>
            <td class='text-left'>
                <?php echo $line->path(); ?>
            </td>
            <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $this->date->month, 1, $this->date->year)); $i++) {
                $date = date("d.m.Y", mktime(0, 0, 0, $this->date->month, $i, $this->year));
                $class = isset($line->stoppedDates[$date]) ? "table-success" : (isWeekend($i, $this->date->month, $this->date->year) ? 'table-danger' : '');
                ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <?php echo isset($operations[$line->id][$date]) ? count($operations[$line->id][$date]) : ""; ?>
                </td>
            <?php } ?>

        </tr>
    <?php } ?>
    </tbody>
</table>

<div class='text-center mt-5 pt-5' id='button-next'><a class="btn btn-primary" href="#" role="button" onclick="window.close(); return false;" >Завершить планирование</a></div>

<script>

</script>