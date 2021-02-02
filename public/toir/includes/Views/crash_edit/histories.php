<table class='table table-bordered table-hover'>
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Дата</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($histories as $history) { ?>
        <tr>
            <td><?php echo $history->ID; ?></td>
            <td><?php echo $history->NAME; ?></td>
            <td><?php echo $history->PLANNED_DATE; ?></td>
            <td class='text-success'>
                <a href="history.php?workshop=<?php echo $history->WORKSHOP_ID; ?>&filter[service_request_id]=<?php echo $history->serviceRequestId; ?>" target=_blank>Выполнено</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>