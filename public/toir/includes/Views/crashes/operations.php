<table class="table table-bordered table-sm" id='table3'>
    <thead>
        <tr class='text-center'>
            <th>№</th>
            <th>Название</th>
            <th>Комментарий</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($operations as $operation) {
		?>
        <tr>
            <td class="text-center"><?php echo $operation->ID; ?></td>
            <td class="text-center"><?php echo $operation->NAME; ?></td>
			<td class="text-center"><?php echo $operation->COMMENT; ?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
