<form action="crash_edit.php" method="post">
<input type="hidden" name="crash" value="<?php echo $this->crash->ID?>">
<input type="hidden" name="save_decision" value="1">
<div class='border mb-4'>
<textarea name="DECISION" class="form-control summernote" rows="5"><?php echo $this->crash->DECISION;?></textarea>
</div>
<input type="submit" class='btn btn-primary' value='Сохранить'>
</form>

<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 300
    });
});
</script>