<style>
.TableComments{
	border: 1px solid gray;
	background: white;
	position:absolute;
    z-index:1000;
}
</style>
<div class="mb-5">
    <h3 data-toggle="collapse" href="#table5" role="button" aria-expanded="false" aria-controls="table5-link">
        Контроль своевременности выполнения задач
        <img src='images/chevron-up.svg' class="ml-2" id="table5-up" style="display:none;">
        <img src='images/chevron-down.svg' class="ml-2" id="table5-down">
    </h3>
    <div class="collapse" id="table5">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2"></th>
                        <th rowspan="2">Коэффициент</th>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <th colspan="2"><?php echo $service->NAME; ?></th>
                        <?php } ?>
						 </tr>
                    <tr class="text-center">
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <th>В срок</th>
                            <th>Просрочено</th>
                        <?php } ?>
					</tr>

                </thead>
                <tbody>
                    <tr class="text-center">
                        <td class="text-left">План работ на день профилактики</td>
                        <td class="font-weight-bold fw-bold text-danger">
                            <?php
                            $dateProcessesPlanDone = $this->analitic->table5PlanedHistories();
                            echo ($dateProcessesPlanDone->done + $dateProcessesPlanDone->notDone)
                                ? number_format($dateProcessesPlanDone->done / ($dateProcessesPlanDone->done + $dateProcessesPlanDone->notDone), 2)
                                : 0;
                            ?>
                        </td>
                        <?php foreach($this->analitic->services() as $service) { 
							$resObj=$this->analitic->table5PlanedHistories($service)?>
                            <td>
							<?php echo $resObj->done; ?>
							</td>
                            <td>
							
							<a href="#" onClick="showTableComments(this); return false">
                                <u class="font-weight-bold"><?php echo $resObj->notDone; ?></u>
                                <img src='images/chat-text.svg' class="ml-2">
							</a>
							<div id="TableComments" style="display:none;" class="TableComments text-left">
                                <button type="button" class="close" aria-label="Close" onclick="$(this).parent().hide();">
                                    <span aria-hidden="true">&times;</span>
                                </button>
							  <table class="table">
								<tr><td>Цех
								<td>Дата/время
								<td>Комментарий
								<? foreach ($resObj->expiredComment  as $k=>$comment){?>
								<tr><td><?php echo $resObj->workshops[$comment['workshop']]->NAME; ?>
								<td><?php echo $comment['date']; ?>
								<td><?php echo $comment['comment']; ?>
								<?}?>
							</table>
						  </div>
						
							
							
							</td>
                        <?php } ?>
						
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Отчет по работам на день профилактики</td>
                        <td class="font-weight-bold fw-bold text-success">
                            <?php
                            $dateProcessesReportDone = $this->analitic->table5ReportedHistories();
                            echo ($dateProcessesReportDone->done + $dateProcessesReportDone->notDone)
                                ? number_format($dateProcessesReportDone->done / ($dateProcessesReportDone->done + $dateProcessesReportDone->notDone), 2)
                                : 0;
                            ?>

                        </td>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <td>
							<?php echo $this->analitic->table5ReportedHistories($service)->done; ?>
							</td>
                            <td>
							<a href="#" onClick="showTableComments(this); return false">
                                <u class="font-weight-bold"><?php echo $this->analitic->table5ReportedHistories($service)->notDone; ?></u>
                                <img src='images/chat-text.svg' class="ml-2">
							</a>
							<div id="TableComments" style="display:none" class="TableComments text-left">
                                <button type="button" class="close" aria-label="Close" onclick="$(this).parent().hide();">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <table class="table">
                                    <tr><td>Цех
                                    <td>Дата/время
                                    <td>Комментарий
                                    <? foreach ($this->analitic->table5ReportedHistories($service)->expiredComment  as $k=>$comment){?>
                                    <tr><td><?php echo $this->analitic->table5ReportedHistories($service)->workshops[$comment['workshop']]->NAME; ?>
                                    <td><?php echo $comment['date']; ?>
                                    <td class="text-left"><?php echo $comment['comment']; ?>
                                    <?}?>
                                </table>
							</td>
                        <?php } ?>
							
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Выполнение операций</td>
                        <td class="font-weight-bold fw-bold text-success"><?php
                            $doneHistories = $this->analitic->table5DoneHistoriesAll();
                            if($doneHistories->done + $doneHistories->notDone > 0) {
                                echo number_format($doneHistories->done / ($doneHistories->done + $doneHistories->notDone), 2);
                            }
                        ?></td>
                        <?php foreach($this->analitic->services() as $service) { 
                            $doneHistories = $this->analitic->table5DoneHistories($service);
                            ?>
                            <td><?php echo $doneHistories->done; ?></td>
                            <td><?php echo $doneHistories->notDone; ?></td>
                        <?php } ?>
						
                    </tr>
                </tbody>
            </table>
        </div>
</div>


<script>
$('#table5').on('show.bs.collapse', function () {
    $('#table5-up').show();
    $('#table5-down').hide();
})

$('#table5').on('hide.bs.collapse', function () {
    $('#table5-up').hide();
    $('#table5-down').show();
})


function showTableComments(el) {		
		
    $('.TableComments').hide();
    var div = $(el).next();
    var left = Math.max($(el).offset().left - div.width(), 0) + 6;
    var top = $(el).position().top - 4;
    div.css('left',left,'width','400px');
    div.show();
    event.stopPropagation();
};

</script>