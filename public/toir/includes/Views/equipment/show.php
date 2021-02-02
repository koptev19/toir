<div class='mb-4'>
	<a onClick="createNode(<?php echo  $node->ID; ?>); return false" href="#" class='btn btn-outline-primary'>Добавить дочерний элемент</a>
	<?php if($node->LEVEL == 1) { ?>
		<a href="index.php?workshop=<?php echo $node->id; ?>" class='btn btn-outline-primary ml-4' target=_blank>Планирование ТОиР</a>
	<?php } ?>
</div>

<?php $this->view('equipment/show/operations', compact('planOperations', 'works', 'node')); ?>

<div class='my-5'>
	<span class='h4'><?php echo $node->NAME; ?> (<?php echo Equipment::$types[$node->TYPE]; ?>)</span> 
	<a href="#" onClick="editNode(<?php echo  $node->ID; ?>); return false" class='ml-4'><img src='./images/pencil.svg' height="16"></a>
</div>

<?php
if($node->TYPE == Equipment::TYPE_WORKSHOP) {
	$this->view('equipment/show/workshop', ['node' => $node]);
}
if($node->TYPE === Equipment::TYPE_LINE) {
	$this->view('equipment/show/line', ['node' => $node]);
}
if($node->TYPE === Equipment::TYPE_MECHANISM) {
	$this->view('equipment/show/mechanism', ['node' => $node]);
}
if($node->TYPE === Equipment::TYPE_NODE) {
	$this->view('equipment/show/node', ['node' => $node]);
}
if($node->TYPE === Equipment::TYPE_DETAIL) {
	$this->view('equipment/show/detail', ['node' => $node]);
}
?>

