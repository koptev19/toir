<?php
/**
 * @param string $name
 * @param Equipment $node
 */

$file = $node->$name ? File::find($node->$name) : null;

if($file) {
    if(FileService::isImage($file)) { ?>
        <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><img src="<?php echo FileService::getUrl($file); ?>" width=100></a>
    <?php } else { ?>
        <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><?php echo basename($file->filename); ?></a>
    <?php }
}
