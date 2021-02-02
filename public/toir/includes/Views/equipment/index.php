<?php $this->view('_header', ['title' => 'Оборудование']); ?>

<script src="scripts/equipment_tree.js"></script>
<link rel="stylesheet" href="styles/equipment.css">

<?php  $this->view("components/tabs"); ?>
<div class="tab-content border border-top-0">
	<div class="tab-pane fade show active" id="equipment" role="tabpanel" aria-labelledby="equipment-tab">
		<div id="tree" class="pan1 py-4">
			<div style='display:block' id='container0' el="0" class='content mb-3'></div>
			<?php if(UserToir::current()->IS_ADMIN) { ?>
				<a href='#' onclick="createNode(0); return false;" class='ml-3'>Добавить цех</a>
			<?php } ?>
		</div>
		<div class="resize"></div>
		<div class="p-4 w-auto main" id="main-equipment">
		</div>
	</div>
</div>


<script>
var selectedItem=<?php echo $selectedItem ?? 0; ?> 
var parents=[];
<?php foreach($parents as $k=>$v )
{
echo "parents[".$k."]=1;";
}
?>
</script>

<?php $this->view('_footer'); ?>

