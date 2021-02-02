<?php
/**
 * @param array $fileId
 */
?>

<?php 
$this->view('equipment/show/params/file_multiple', compact('node', 'name')); 
?>

<div>
    Добавить файлы:
    <input type="file" multiple name="<?php echo $name; ?>[]" class="form-control" />
</div>
