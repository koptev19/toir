<?php
$this->view('_header', ['title' => "Планирование на ". monthName($this->date->month) . " " . $this->date->year . " (" . $this->workshop->NAME . ")"]);
?>

<h1 class='text-center'>Планирование на <?php echo monthName($this->date->month) ?> <?php echo $this->date->year; ?></h1>