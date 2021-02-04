<?php 
	$tabs =[
		["name"=>"Оборудование","url"=>"/equipments","admin"=>0],
		["name"=>"Службы","url"=>"/departments","admin"=>1],
		["name"=>"Пользователи","url"=>"users.php","admin"=>1],
		["name"=>"Настройки","url"=>"/settings","admin"=>1],
		["name"=>"Приемка оборудования","url"=>"accept.php","admin"=>1],
//		["name"=>"Приемка оборудования","url"=>"accept_item.php","admin"=>0],
	]
?>
<ul class="nav nav-tabs" role="tablist">
	<?php foreach ($tabs as $tab){ 
		if(!UserToir::current()->IS_ADMIN && $tab['admin']) continue;
		if (strpos($_SERVER['REQUEST_URI'], $tab['url']) !== false){
			$class= "active";
			$href = "#";
		}else{
			$class= "";
			$href = $tab['url'] ;
		}	
	?>
	<li class="nav-item" role="presentation">
    	<a class="nav-link <?php echo $class ?>" href="<?php echo $href ?>"><?php echo $tab['name'] ?></a>
	</li>
	<?php } ?>
</ul>

