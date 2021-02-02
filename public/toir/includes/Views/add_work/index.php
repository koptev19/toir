<?php 
$this->view('_header', ['title' => "Добавление работы без даты"]);

if($_SESSION['add_work_errors'] && is_array($_SESSION['add_work_errors']) && count($_SESSION['add_work_errors'])){
    echo '<div class="alert alert-danger">';
	echo implode("<br>", $_SESSION['add_work_errors']);
	echo "</div>";
    $_SESSION['add_work_errors'] = null;
}	  
?>

<h1 class='text-center mb-5'>Добавление операции без даты</h1>
<form  method="post" action="add_work.php">
<input type="hidden" name="action" value="store">

<?php $this->view('add_work/_add', ['equipment' => $this->equipment]); ?>

<input value="Добавить" type="submit" class='btn btn-primary'>
</form>

<?php $this->showFooter(); ?>
