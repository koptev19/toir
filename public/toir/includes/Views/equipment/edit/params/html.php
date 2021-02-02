<?php
/**
 * @param string $name
 * @param Equipment $node
 */
?>

<textarea name="<?php echo $name; ?>" class="form-control summernote"><?php echo $node->$name; ?></textarea>

<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 300
    });
});
</script>