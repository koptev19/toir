<html>
<head>
<title>График обслуживания &quot;<?php echo $this->workshop->NAME;?>&quot; на <?php echo monthName($this->month) . " " . $this->year; ?></title>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</head>
<body>
<style>
@media print {
   body {
      -webkit-print-color-adjust: exact !important;
   }
   .table{
	font-size:12px;
   }
   .table td.table-secondary{
		background-color:#CECECE !important;
   }

   @page {
		size:landscape;
	}
}
</style>

<div class='text-right mb-4'>
    УТВЕРЖДАЮ<br>
	Директор по производству ЗАО "Плайтера"<br>	
	________________________ М.Н. Чуринова<br>
	____   ________________  <?php echo $this->year;?>

</div>
<div class='table-secondary1 text-center mb-1 '>
    <b>График обслуживания линий "<?php echo $this->workshop->NAME; ?>" на <?php echo monthName($this->month)." ".$this->year; ?></b>
</div>

<table class='table table-sm table-bordered table1' id='table1_1'>
    <thead>
        <tr>
            <th rowspan="2">№</th>
            <th rowspan="2">Наименование</th>
            <th colspan="100%" class='text-center'>
                       числа месяца
                     
            </th>
        </tr>
        <tr class='text-center'><?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $this->month, 1, $this->year)); $i++){
            $class = isWeekend($i, $this->month, $this->year) ? 'table-secondary' : '';
            ?>
            <td class='<?php echo $class; ?>'>
                <?php echo ($i<10)?"&nbsp;".$i."&nbsp;":$i; ?>
            </td>
        <?php } ?>
        </tr>
    </thead>
	<tbody>
	<?php foreach($this->lines as $line) { ?>
        <tr class='text-center'>
            <td><?php echo $line->ID; ?></td>
            <td class='text-left'> <?php echo $line->NAME; ?></td>
            <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $this->month, 1, $this->year)); $i++) {
                $date = date("Y-m-d", mktime(0, 0, 0, $this->month, $i, $this->year));
                $class = isWeekend($i, $this->month, $this->year) ? 'table-secondary' : '';
            ?>
                <td class='text-nowrap align-middle <?php echo $class; ?>'>
                      <?php if (isset($line->stoppedDates[$date])) { ?>
                        X
                    <?php } ?>
                </td>
            <?php } ?>

        </tr>
    <?php } ?>
    </tbody>
</table>

<script>
self.print();
</script>
</body>
</html>