<h5 class='text-center mt-5'>Шаг 2 - прижать операции к датам остановки</h5>

<?php if($_SESSION['pushErrors']) { ?>
<div class='alert alert-danger text-center my-5'>
    <?php foreach($_SESSION['pushErrors'] as $error) ?>
	<div><?php echo $error['error']; ?></div>

</div>
<?php unset($_SESSION['pushErrors']);
} ?>

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
                $date = date("Y-m-d", mktime(0, 0, 0, $this->date->month, $i, $this->date->year));
                $class = isset($line->stoppedDates[$date]) ? "table-success" : (isWeekend($i, $this->date->month, $this->date->year) ? 'table-danger' : '');
                ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <?php if (isset($operations[$line->id][$date])){
							if(!$line->stoppedDates[$date]){ ?>
                            <div class="dropdown">
                                <a class="btn dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="link<?php echo date("dm", mktime(0, 0, 0, $month, $i, $year)); ?>">1</a>
                                <ul class="dropdown-menu" aria-labelledby="link<?php echo date("dm", mktime(0, 0, 0, $month, $i, $year)); ?>">
                                    <li><a class="dropdown-item" href="#" onClick="createStop(<?php echo $line->id; ?>, '<?php echo $date; ?>'); return false">Создать день остановки</a></li>
                                    <li><a class="dropdown-item" href="?action=pushToLeft&line=<?php echo $line->id; ?>&date=<?php echo $date; ?>&workshop=<?php echo $_REQUEST['workshop']; ?>">Прижать влево</a></li>
                                    <li><a class="dropdown-item" href="?action=pushToRight&line=<?php echo $line->id; ?>&date=<?php echo $date; ?>&workshop=<?php echo $_REQUEST['workshop'];?>">Прижать вправо</a></li>
                                </ul>
                            </div>
                        <?php } else {
                            echo count($operations[$line->id][$date]);
                         }}?>
                </td>
            <?php } ?>

        </tr>
    <?php } ?>
    </tbody>
</table>

<div class='text-center mt-5 pt-5' id='button-next'><a class="btn btn-primary" href="?step=2&save=1&workshop=<?php echo $this->workshop->ID;?>" role="button">Прижать влево и перейти на шаг 3</a></div>

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
