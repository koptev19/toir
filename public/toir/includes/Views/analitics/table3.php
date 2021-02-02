<div class="mb-5">
    <h3 data-bs-toggle="collapse" href="#table3" role="button" aria-expanded="false" aria-controls="table3-link">
        Детализация причин простоев
        <img src='images/chevron-up.svg' class="ml-2" id="table3-up" style="display:none;">
        <img src='images/chevron-down.svg' class="ml-2" id="table3-down">
    </h3>
    <div class="collapse" id="table3">
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2"></th>
                        <th rowspan="2">Количество часов</th>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <th colspan="2"><?php echo $service->SHORT_NAME ? $service->SHORT_NAME : $service->NAME; ?></th>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <th>Оборудование</th>
                            <th>Описание</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td colspan="100%" class="table-info">Журнал простоев</td>
                    </tr>
                    <tr>
                        <td>Аварии</td>
                        <td class="text-center"><?php echo $this->analitic->table2_1CrashesTimesString(); ?></td>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <td><?php echo $this->analitic->table3CrashEquipments($service); ?></td>
                            <td><?php echo $this->analitic->table3CrashDescriptions($service); ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td>ППР</td>
                        <td class="text-center"><?php echo $this->analitic->table2_2CrashHistoryTimesString(null, false); ?></td>
                        <?php foreach($this->analitic->services() as $service) { 
                            $table3Histories = $this->analitic->table3HistoryEquipmentsNames($service);
                            ?>
                            <td><?php echo $table3Histories->equipments; ?></td>
                            <td><?php echo $table3Histories->names; ?></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td>Настройка оборудования</td>
                        <td class="text-center">&nbsp;</td>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td>Прочие</td>
                        <td class="text-center">&nbsp;</td>
                        <?php foreach($this->analitic->services() as $service) { ?>
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
$('#table3').on('show.bs.collapse', function () {
    $('#table3-up').show();
    $('#table3-down').hide();
})

$('#table3').on('hide.bs.collapse', function () {
    $('#table3-up').hide();
    $('#table3-down').show();
})
</script>