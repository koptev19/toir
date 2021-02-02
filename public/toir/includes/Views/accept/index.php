<h5 class='text-center mb-4'>
    Приемка оборудования
</h5>

<a href="?action=newAccept" class='btn btn-outline-primary'>Новая</a>

<table class="table table-bordered mt-4 table-hover">
    <thead>
        <tr class='text-center'>
            <th><div>№</div></th>
            <th><div>Оборудование</div></th>
          	<th><div>Ссылка</div></th>
            <th><div>Приемка</div></th>
			<th></th>
        </tr>
    </thead>
    <tbody>
		<?php foreach ($accepts as $accept){ ?>
			<tr class='text-center'>
                <td><?php echo $accept->ID?></td>
                <td><?php echo $equipments[$accept->EQUIPMENT_ID]->path(); ?></td>
          		<td>
					<?php 
					$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>
					<input class="form-control" id="copyHref<?php echo $accept->ID ?>" type="text" value = "<?php echo $url."/toir/accept_item.php?id=".$accept->ID ?>"  readonly><br>
					<a href="#" onClick='copyHref("copyHref<?php echo $accept->ID ?>"); return false'>скопировать ссылку</a>
				</td>         
	            <td><a href='accept_item.php?id=<?php echo $accept->ID ?>' class="btn btn-primary" target=_blank>Принять</a></td>         
				<th>
					<a href="accept.php?action=edit&id=<?php echo $accept->ID ?>"><img src="./images/pencil.svg"></a>
					<a href="accept.php?action=delete&id=<?php echo $accept->ID ?>"
					onclick="return confirm('Удалить?')" class="ml-3"><img src="./images/x.svg"></a>
				</th>
			</tr>
		<?php }?>
    </tbody>
</table>

<script>
function copyHref(id) {
  var copyText = document.getElementById(id);
  copyText.select();
  document.execCommand("copy");
  alert("Скопировано в буфер: " + copyText.value);
}
</script>

