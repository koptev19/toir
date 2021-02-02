<div class="mb-5">
    <h3 data-bs-toggle="collapse" href="#table2" role="button" aria-expanded="false" aria-controls="table2-link">
        Простои по цехам и линиям
        <img src='images/chevron-up.svg' class="ml-2" id="table2-up" style="display:none;">
        <img src='images/chevron-down.svg' class="ml-2" id="table2-down">
    </h3>
    <div class="collapse" id="table2">
        <div class="table-responsive mb-4">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2"></th>
                        <th colspan="3">Всего</th>
                        <th colspan="3">ППР</th>
                        <th colspan="3">Авария</th>
                        <th colspan="3">Прочие</th>
                    </tr>
                    <tr class="text-center">
                        <th>Длительность простоя, час</th>
                        <th>Исполнители, человеко-часы</th>
                        <th>Запчасти, руб.</th>
                        <th>Длительность простоя, час</th>
                        <th>Исполнители, человеко-часы</th>
                        <th>Запчасти, руб.</th>
                        <th>Длительность простоя, час</th>
                        <th>Исполнители, человеко-часы</th>
                        <th>Запчасти, руб.</th>
                        <th>Длительность простоя, час</th>
                        <th>Исполнители, человеко-часы</th>
                        <th>Запчасти, руб.</th>
                    </tr>
                </thead>
                <tbody>
					<?php foreach($this->analitic->workshops() as $workshop) { ?>
                    <tr class="text-center">
                        <td class="text-left text-nowrap">
                            <a href="#" onclick="table2ShowLines(<?php echo $workshop->ID; ?>); return false;"><?php echo $workshop->NAME; ?></a>
                            , в т.ч.
                            <img src="images/chevron-up.svg" class="table2-up-<?php echo $workshop->ID; ?>" style="display:none;">
                            <img src="images/chevron-down.svg" class="table2-down-<?php echo $workshop->ID; ?>">
                        </td>
                        <td><?php echo $this->analitic->table2_1AllTimesString($workshop); ?></td>
                        <td><?php echo $this->analitic->table2_2WorkersHoursString($workshop); ?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $this->analitic->table2_1HistoryTimesString($workshop); ?></td>
                        <td><?php echo $this->analitic->table2_2WorkersHoursString($workshop); ?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $this->analitic->table2_1CrashesTimesString($workshop); ?></td>
                        <td>?</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                        <?php foreach($workshop->lines as $line) { ?>
                        <tr class="text-center table2-equipment-<?php echo $workshop->ID; ?>" style="display:none;">
                            <td class="text-left pl-4"><?php echo $line->NAME; ?></td>
                            <td><?php echo $this->analitic->table2_1AllTimesString($workshop, $line); ?></td>
                            <td><?php echo $this->analitic->table2_2WorkersHoursString($workshop, $line); ?></td>
                            <td>&nbsp;</td>
                            <td><?php echo $this->analitic->table2_1HistoryTimesString($workshop, $line); ?></td>
                            <td><?php echo $this->analitic->table2_2WorkersHoursString($workshop, $line);?></td>
                            <td>&nbsp;</td>
                            <td><?php echo $this->analitic->table2_1CrashesTimesString($workshop, $line); ?></td>
                            <td>?</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <h3 class="mb-3 mt-5">Простои по службам</h3>
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2"></th>
                        <th>Всего</th>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <th><?php echo $service->SHORT_NAME ? $service->SHORT_NAME : $service->NAME; ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td colspan="100%" class="table-info">Анализ ППР</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Плановые операции</td>
                        <td><?php echo $this->analitic->table2_2HistoryTimesString(null, true); ?></td>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <td><?php echo $this->analitic->table2_2HistoryTimesString($service, true); ?></td>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Внеплановые операции</td>
                        <td><?php echo $this->analitic->table2_2HistoryTimesString(null, false); ?></td>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <td><?php echo $this->analitic->table2_2HistoryTimesString($service, false); ?></td>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <td colspan="100%" class="table-info">Журнал простоев</td>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Аварии</td>
                        <td><?php echo $this->analitic->table2_1CrashesTimesString(); ?></td>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <td><?php echo $this->analitic->table2_1CrashesTimesString(null, null, $service); ?></td>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Настройка оборудования</td>
                        <td>&nbsp;</td>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <td>&nbsp;</td>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <td class="text-left">Прочие</td>
                        <td>&nbsp;</td>
                        <?php foreach($this->analitic->services() as $service) { ?>
                            <td>&nbsp;</td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$('#table2').on('show.bs.collapse', function () {
    $('#table2-up').show();
    $('#table2-down').hide();
})

$('#table2').on('hide.bs.collapse', function () {
    $('#table2-up').hide();
    $('#table2-down').show();
})

function table2ShowLines(workshopId)
{
    $('.table2-equipment-' + workshopId).toggle();
    $('.table2-up-' + workshopId).toggle();
    $('.table2-down-' + workshopId).toggle();
}
</script>