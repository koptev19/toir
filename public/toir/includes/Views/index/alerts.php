<div id='index-alerts-fixed' class='d-none position-fixed' style="z-index:100; top:0px; right:80px; left:80px;">
</div>
<div id='index-alerts'>
<?php if ($createServiceRequest) { ?>
    <div class='mb-2 alert alert-danger'><a href="log_receiving.php" target=_blank class='text-dark'>Замечания по приемке оборудования: <?php echo $createServiceRequest; ?></a></div>
<?php } ?>

<?php if ($countRepairRequest) { ?>
    <div class='mb-2 alert alert-danger'><a href="service_request.php" target=_blank class='text-dark'>Заявок на ремонт: <?php echo $countRepairRequest; ?></a></div>
<?php } ?>

<?php if ($masterPlanDate) { 
    $classAlert = count($masterPlanDate) == 1 && reset($masterPlanDate) == date("Y-m-d") ? 'alert-success' : 'alert-danger';
    ?>
    <div class='mb-2 alert <?php echo $classAlert; ?>'>Составить план работ на день профилактики: 
    <?php foreach($masterPlanDate as $date => $dateProcesses) {
        $class = date("Y-m-d", strtotime($date)) < date("Y-m-d", time() + 60*60*24) ? "text-danger" : "text-primary";
        ?>
        <a class="ml-4 <?php echo $class; ?>" href="#" onclick="selectServiceOpen('master_plan_date.php', '<?php echo $date ?>', 'plan'); return false;"><?php echo date("d.m", strtotime($date)); ?></a>
    <?php } ?>
    </div>
<?php } ?>

<?php if ($masterReportDate) { 
    $classAlert = count($masterReportDate) == 1 && reset($masterReportDate) == date("Y-m-d") ? 'alert-success' : 'alert-danger';
    ?>
    <div class='mb-2 alert <?php echo $classAlert; ?>'>Заполнить отчет "План работ на день профилактики": 
    <?php foreach($masterReportDate as $date) {
        $class = date("Y-m-d", strtotime($date)) >= date("Y-m-d") ? "text-primary" : "text-danger";
        ?>
        <a href="#" class='mr-3 <?php echo $class; ?>' onclick="selectServiceOpen('master_report_date.php', '<?php echo $date ?>', 'report'); return false;"><?php echo date("d.m", strtotime($date)); ?></a>
    <?php } ?>
    </div>
<?php } ?>

<?php if ($operationsNotPush) { ?>
    <div class='mb-2 alert alert-danger'><a href="#" onclick="filterNotPush(<?php echo $this->workshop->ID; ?>, <?php echo $this->year; ?>, <?php echo $this->month; ?>); return false;" class='text-dark'>Не прижатых операций: <?php echo $operationsNotPush; ?></a></div>
<?php } ?>

<?php if ($operationsNotDone) { ?>
    <div class='mb-2 alert alert-danger'><a href="?workshop=<?php echo $this->workshop->ID; ?>&year=<?php echo $this->year; ?>&month=<?php echo $this->month; ?>&table2=notdone" class='text-dark'>Невыполнено операций: <?php echo $operationsNotDone; ?></a></div>
<?php } ?>

<?php if (!empty($crashesNotDone)) { 
    ?>
    <div class='mb-2 alert alert-danger'>
        Авария: 
        <?php $crashNum = 0;
        foreach($crashesNotDone as $crashStatus => $count) { 
            $crashNum++;
            echo ($crashNum > 1 ? ", " : "") . '<a href="crashes.php?workshop=' . $this->workshop->ID. '&status=' . $crashStatus . '" target=_blank class="text-dark">' . Crash::verbalStatus($crashStatus)." (" . $count . ")</a>";
        } ?>
        </a>
    </div>
<?php } ?>

<?php if($showPlanMonth) { ?>
    <div class='mb-2 alert alert-danger'>
        <a href="plan_month.php?workshop=<?php echo $this->workshop->ID; ?>" class="text-danger">Составить "График ТОиР" на <?php echo monthName($showPlanMonth->month) . ' ' . $showPlanMonth->year; ?></a>
    </div>
<?php } ?>

<?php if($planCheck) { ?>
    <div class='mb-2 alert alert-danger'>
        Проверить планирование служб: 
        <?php foreach($planCheck as $dateLink => $dateView) { ?>
            <a href="plan_date_checking.php?date=<?php echo $dateLink; ?>" class="mx-2" target=_blank><?php echo $dateView; ?></a>
        <?php } ?>
    </div>
<?php } ?>
</div>

<script>
window.addEventListener('scroll', function() {
    if (pageYOffset > 150) {
        $('#index-alerts-fixed').removeClass('d-none');
        $('#index-alerts').addClass('invisible');
    } else {
        $('#index-alerts-fixed').addClass('d-none');
        $('#index-alerts').removeClass('invisible');
    }
});

$(document).ready(function() {
    $('#index-alerts-fixed').html($('#index-alerts').html());
});

</script>