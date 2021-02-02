<table class='table table-hover table-sm table-borderless'>
<?php foreach($services as $service) { ?>
    <tr>
        <td class='px-3'><a href="#" onclick="editService(<?php echo $service->ID; ?>); return false;"><?php echo $service->NAME; ?></a></td>
    </tr>
<?php } ?>
</table>