<?php
if(count($operationsInLine)) {

    ?>

    <div class="table-responsive mb-3 table-thead-fixed">
    <table class="table table-bordered table-sm table-hover" id='table-dates'>
        <thead>
            <tr class='text-center'>
                <th><div>Наименование оборудования</div></th>
                <th><div>Название регламентной операции</div></th>
                <th><div>Тип операции</div></th>
                <th><div>Планируемая дата</div></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($operationsInLine as $lineName => $operations) { ?>
            <tr>
                <td class='table-warning text-center' colspan=100%>
                    <?php echo $lineName; ?>
                </td>
            </tr>
            <?php foreach($operations as $operation) { ?>
            <tr id='operation-<?php echo $operation->ID; ?>' operation="<?php echo $operation->ID; ?>">
                <td><?php echo $operation->equipment ? $operation->equipment->path() : ''; ?></td>
                <td><?php echo $operation->NAME; ?></td>
                <td class='text-center'><?php echo $operation->TYPE_OPERATION; ?></td>
                <td class='text-center'><?php echo $operation->PLANNED_DATE; ?></td>
            </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
    </div>

    <div class='mt-4 text-center'>
        <a href='?mode=<?php echo $this->mode; ?>&step=1&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>' class='btn btn-primary mr-5'>Продолжить планирование</a>
        <a href='#' class='btn btn-outline-secondary ml-5' onclick="opener.location.href = opener.location.href; self.close(); return false;">Завершить Планирование</a>
    </div>
<?php } else { ?>
    <script>
    opener.location.href = opener.location.href; self.close();
    </script>
<?php } ?>
