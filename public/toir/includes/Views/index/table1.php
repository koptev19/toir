<?php
global $USER;
?>

<div class="mytofilter">
<?
$this->view('index/filter', compact('allWorkshops', 'services', 'mekhannik'));
?>
</div>

<h3 class='text-center mt-4'>
    График ТОиР
    <a href="get_table1_pdf.php?workshop=<?php echo $this->workshop->ID; ?>&month=<?php echo $this->month;?>&year=<?php echo $this->year;?>"  target="_blank" class='ml-3'><img src="./images/print.svg"></a> 
</h3>

<table class='table table-sm table-bordered table1 ' id='table1_1'>
    <thead>
        <tr>
            <th rowspan="3" class="text-center">№</th>
            <th rowspan="3">Наименование</th>
            <th colspan="100%" class='text-center'>
                <div class="btn-group">
                    <button type="button" class="btn" onclick="chDate(<?php echo $this->month > 1 ? $this->month - 1 : 12; ?>, <?php echo $this->month > 1 ? $this->year : $this->year - 1; ?>); return false;"><img src="./images/arrow-left.svg" /></button>
                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?php echo monthName($this->month); ?></button>
                    <ul class="dropdown-menu">
                        <?php for ($i = 1; $i <= 12; $i++) { ?>
                            <a class="dropdown-item <?php echo $i == $this->month ? "active" : ""; ?>" href="#" onclick="chDate(<?php echo $i; ?>, <?php echo $this->year; ?>); return false;"><?php echo monthName($i); ?></a>
                        <?php } ?>
                    </ul>
                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $this->year; ?></button>
                    <ul class="dropdown-menu">
                        <?php for ($i = min($this->year, date("Y")) - 3; $i <= max($this->year, date("Y")) + 1; $i++) { ?>
                            <a class="dropdown-item <?php echo $i == $this->year ? "active" : ""; ?>" href="#" onclick="chDate(<?php echo $this->month; ?>, <?php echo $i; ?>); return false;"><?php echo $i; ?></a>
                        <?php } ?>
                    </ul>
                    <button type="button" class="btn" onclick="chDate(<?php echo $this->month < 12 ? $this->month + 1 : 1; ?>, <?php echo $this->month < 12 ? $this->year : $this->year + 1; ?>); return false;"><img src="./images/arrow-right.svg" /></button>
                </div>
            </th>
        </tr>
        <tr class='text-center'><?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $this->month, 1, $this->year)); $i++){
            $class = isWeekend($i, $this->month, $this->year) ? 'table-danger' : '';
            ?>
            <td class='<?php echo $class; ?>'>
                <?php echo $i; ?>
            </td>
        <?php } ?>
        </tr>
        <tr class='text-center'>
        <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $this->month, 1, $this->year)); $i++) {
            $date = date("Y-m-d", mktime(0, 0, 0, $this->month, $i, $this->year));
            $class = $this->getClassByDateProcess($dateProcesses, $date, $masterPlanDate);
            ?>
            <td class="<?php echo $class; ?>">	
                <?php if (isset($dateProcesses[$date]) && (in_array(DateProcess::STAGE_NEW, $dateProcesses[$date]) || in_array(DateProcess::STAGE_PLAN_REJECTED, $dateProcesses[$date]))) {  ?>
                    <a href="#" onclick="selectServiceOpen('master_plan_date.php', '<?php echo $date ?>', 'plan'); return false;"><img src="./images/card-list.svg"></a>
                <?php } ?>
                <?php if (isset($dateProcesses[$date]) && in_array(DateProcess::STAGE_PLAN_APPROVED, $dateProcesses[$date])) { ?>
                    <a href="#" onclick="selectServiceOpen('master_report_date.php', '<?php echo $date ?>', 'report'); return false;"><img src="./images/calendar-check.svg"></a>
                <?php } ?>
            </td>
        <?}?>
        </tr>
    </thead>


    <tbody>
	<?php foreach($this->lines as $line) { ?>
        <tr class='text-center'>
            <td><?php echo $line->ID; ?></td>
            <td class='text-left'>
                <?php echo $line->path(); ?>
            </td>
            <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $this->month, 1, $this->year)); $i++) {
                $date = date("Y-m-d", mktime(0, 0, 0, $this->month, $i, $this->year));
                $class = $this->getClassByDateProcess($dateProcesses, $date, $masterPlanDate);
                ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <?php if (isset($line->countInDates[$date]) && $line->countInDates[$date] > 0) { ?>
                        <a target="_blank" href="work_plan.php?date=<?php echo $date; ?>&workshop=<?php echo $this->workshop->ID; ?>&service=<?php echo $this->filter['SERVICE_ID']; ?>" table="_blank" class='<?php echo isset($line->stoppedDates[$date]) ? "" : "text-danger font-weight-bold"; ?>'><?php echo $line->countInDates[$date]; ?></a>
                    <?php } ?>
                    <?php if(isset($line->stoppedDates[$date]) && $line->stoppedDates[$date]->CRASH_ID) { ?>
                        <a href="crashes.php?workshop=<?php echo $this->workshop->ID; ?>&crash=<?php echo $line->stoppedDates[$date]->CRASH_ID; ?>" class="mx-2 text-danger" target=_blank>А</a>
                    <?php } ?>
                </td>
            <?php } ?>

        </tr>
    <?php } ?>
    </tbody>
</table>

<?php
$nextMonth = $this->month < 12 ? $this->month + 1 : 1;
$nextYear = $this->month < 12 ? $this->year : $this->year + 1;
?>

<table class='table table-sm table-bordered table1' id='table1_2'>
    <thead>
        <tr>
            <th rowspan="3">№</th>
            <th rowspan="3">Наименование</th>
            <th colspan="100%" class='text-center'>
                <?php echo monthName($nextMonth); ?>
                <?php echo $nextYear; ?>
                <a href="#" data-workshop='<?php echo $this->workshop->ID; ?>' onclick="toggleTable1_2(this); return false;" class='h6 ml-2 text-primary'><?php echo($_COOKIE["table1" . $this->workshop->ID] == "show") ? "скрыть": "показать"; ?></a>
            </th>
        </tr>
        <tr class='text-center'><?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $nextMonth, 1, $nextYear)); $i++){
            $class = isWeekend($i, $nextMonth, $nextYear) ? 'table-danger' : '';
            ?>
            <td class='<?php echo $class; ?>'>
                <?php echo $i; ?>
            </td>
        <?php } ?>
        </tr>
        <tr class='text-center'>
        <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $nextMonth, 1, $nextYear)); $i++) {
            $date = date("Y-m-d", mktime(0, 0, 0, $nextMonth, $i, $nextYear));
            $class = $this->getClassByDateProcess($dateProcesses, $date, $masterPlanDate);
            ?>
            <td class="<?php echo $class; ?>">	
                <?php if (isset($dateProcesses[$date]) && (in_array(DateProcess::STAGE_NEW, $dateProcesses[$date]) || in_array(DateProcess::STAGE_PLAN_REJECTED, $dateProcesses[$date]))) {  ?>
                    <a href="#" onclick="selectServiceOpen('master_plan_date.php', '<?php echo $date ?>', 'plan'); return false;"><img src="./images/card-list.svg"></a>
                <?php } ?>
                <?php if (isset($dateProcesses[$date]) && in_array(DateProcess::STAGE_PLAN_APPROVED, $dateProcesses[$date])) { ?>
                    <a href="#" onclick="selectServiceOpen('master_report_date.php', '<?php echo $date ?>', 'report'); return false;"><img src="./images/calendar-check.svg"></a>
                <?php } ?>
            </td>
        <?}?>
        </tr>
    </thead>


    <tbody>
    <?php foreach($this->lines as $line) { ?>
        <tr class='text-center table-row' style="<?php echo($_COOKIE["table1" . $this->workshop->ID] == "show") ? "": "display: none"; ?>">
            <td><?php echo $line->ID; ?></td>
            <td class='text-left'>
                <a href="/equipments?id=<?php echo $line->ID; ?>" target=_blank><?php echo $line->NAME; ?></a>
            </td>
            <?php for($i = 1; $i <= date('t', mktime(0, 0, 0, $nextMonth, 1, $nextYear)); $i++) {
                $date = date("Y-m-d", mktime(0, 0, 0, $nextMonth, $i, $nextYear));
                $class = $this->getClassByDateProcess($dateProcesses, $date, $masterPlanDate);
                ?>
                <td class='text-nowrap <?php echo $class; ?>'>
                    <?php if (isset($line->countInDates[$date]) && $line->countInDates[$date] > 0) { ?>
                        <a target="_blank" href="work_plan.php?date=<?php echo $date; ?>&workshop=<?php echo $this->workshop->ID; ?>&service=<?php echo $this->filter['SERVICE_ID']; ?>" target="_blank" class='<?php echo isset($line->stoppedDates[$date]) ? "" : "text-danger font-weight-bold"; ?>'><?php echo $line->countInDates[$date]; ?></a>
                    <?php } ?>
                </td>
            <?php } ?>

        </tr>
    <?php } ?>
    </tbody>
</table>

<div class="modal fade" id="crashModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="crashModalContent">
      </div>
    </div>
  </div>
</div>
