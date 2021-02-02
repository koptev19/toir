<h5 class='text-center mt-5'>Шаг 1 - отметить даты остановки линий</h5>

<form method="post" action="">
<input type="hidden" name="step" value='1'>
<input type="hidden" name="save" value='1'>
<input type="hidden" name="workshop" value='<?php echo $this->workshop->ID; ?>'>
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
                $date = date("d.m.Y", mktime(0, 0, 0, $this->date->month, $i, $this->date->year));
                $class = isWeekend($i, $this->date->month, $this->date->year) ? 'table-danger' : '';
                ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <input type="hidden" name="stop[<?php echo $line->ID; ?>][<?php echo $date; ?>]" value='0'>
                    <input type="checkbox" name="stop[<?php echo $line->ID; ?>][<?php echo $date; ?>]" value='1'>
                </td>
            <?php } ?>

        </tr>
    <?php } ?>
    </tbody>
</table>

<div class='text-center mt-5 pt-5' id='button-next'><button type="submit" class="btn btn-primary">Сохранить и перейти на шаг 2</button></div>

</form>