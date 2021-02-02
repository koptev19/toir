<div class="mb-5">
    <h3 data-bs-toggle="collapse" href="#table4" role="button" aria-expanded="false" aria-controls="table4-link">
        Время выполнения операций план / факт
        <img src='images/chevron-up.svg' class="ml-2" id="table4-up" style="display:none;">
        <img src='images/chevron-down.svg' class="ml-2" id="table4-down">
    </h3>
    <div class="collapse" id="table4">
        <div class="table-responsive mb-4">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2">Оборудование</th>
                        <th colspan="3">Плановые</th>
                        <th colspan="3">Внеплановые</th>
                    </tr>
                    <tr class="text-center">
                        <th>Количество</th>
                        <th>Время плановое</th>
                        <th>Время фактическое</th>
                        <th>Количество</th>
                        <th>Время плановое</th>
                        <th>Время фактическое</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($this->analitic->workshops() as $workshop) { 
                        $this->view('analitics/table4_equipment', ['equipment' => $workshop]);
                    } ?>
                </tbody>
            </table>
        </div>

        <h3 class="mt-5 mb-3">Количество новых операций с <?php echo date("d.m.Y", strtotime($this->dateFrom)); ?> по <?php echo date("d.m.Y", strtotime($this->dateTo)); ?></h3>
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2"></th>
                        <th colspan="2">По компании</th>
                        <?php foreach($this->analitic->workshops() as $workshop) { ?>
                            <th colspan="2"><?php echo $workshop->NAME?></th>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <th>За период</th>
                        <th>Всего</th>
                        <?php foreach($this->analitic->workshops() as $workshop) { ?>
                            <th>За период</th>
                            <th>Всего</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td colspan="100%" class="table-info">Причины возникновения операций</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left"><?php echo Operation::verbalReason(Operation::REASON_INSTRUCTION); ?></td>
                        <td><?php echo $this->analitic->table4_1CountPeriod(Operation::REASON_INSTRUCTION); ?></td>
                        <td><?php echo $this->analitic->table4_1CountAll(Operation::REASON_INSTRUCTION); ?></td>
                        <?php foreach($this->analitic->workshops() as $workshop) { ?>
                            <td><?php echo $this->analitic->table4_1CountWorkshopPeriod(Operation::REASON_INSTRUCTION, $workshop); ?></td>
                            <td><?php echo $this->analitic->table4_1CountWorkshopAll(Operation::REASON_INSTRUCTION, $workshop); ?></td>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left"><?php echo Operation::verbalReason(Operation::REASON_VIEW); ?>, в том числе</td>
                        <td><?php echo $this->analitic->table4_1CountPeriod(Operation::REASON_VIEW); ?></td>
                        <td><?php echo $this->analitic->table4_1CountAll(Operation::REASON_VIEW); ?></td>
                        <?php foreach($this->analitic->workshops() as $workshop) { ?>
                            <td><?php echo $this->analitic->table4_1CountWorkshopPeriod(Operation::REASON_VIEW, $workshop); ?></td>
                            <td><?php echo $this->analitic->table4_1CountWorkshopAll(Operation::REASON_VIEW, $workshop); ?></td>
                        <?php } ?>
                    </tr>
                    <?php foreach($this->analitic->services() as $service) { ?>
                        <tr class="text-center">
                            <td class="text-left pl-4">- <?php echo $service->NAME; ?></td>
                            <td><?php echo $this->analitic->table4_1CountPeriod(Operation::REASON_VIEW, $service); ?></td>
                            <td><?php echo $this->analitic->table4_1CountAll(Operation::REASON_VIEW, $service); ?></td>
                            <?php foreach($this->analitic->workshops() as $workshop) { ?>
                                <td><?php echo $this->analitic->table4_1CountWorkshopPeriod(Operation::REASON_VIEW, $workshop, $service); ?></td>
                                <td><?php echo $this->analitic->table4_1CountWorkshopAll(Operation::REASON_VIEW, $workshop, $service); ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    <tr class="text-center">
                        <td class="text-left"><?php echo Operation::verbalReason(Operation::REASON_CRASH); ?></td>
                        <td><?php echo $this->analitic->table4_1CountCrashPeriod(null, true); ?></td>
                        <td><?php echo $this->analitic->table4_1CountCrashPeriod(null, false); ?></td>
                        <?php foreach($this->analitic->workshops() as $workshop) { ?>
                            <td><?php echo $this->analitic->table4_1CountCrashPeriod($workshop, true); ?></td>
                            <td><?php echo $this->analitic->table4_1CountCrashPeriod($workshop, false); ?></td>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Удалено</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <?php foreach($this->analitic->workshops() as $workshop) { ?>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>        
        
    </div>
</div>

<script>
$('#table4').on('show.bs.collapse', function () {
    $('#table4-up').show();
    $('#table4-down').hide();
})

$('#table4').on('hide.bs.collapse', function () {
    $('#table4-up').hide();
    $('#table4-down').show();
})
</script>