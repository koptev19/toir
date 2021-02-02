<?php
/**
 * @param string $name
 * @param Equipment $node
 */

$filesId = $node->$name ? json_decode($node->$name, true) : [];

foreach($filesId as $fileId) {
    $file = File::find($fileId);
    if(FileService::isImage($file)) { ?>
        <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><img src="<?php echo FileService::getUrl($file); ?>" width=150 class="mb-3 mr-3"></a>
    <?php } else { ?>
        <a href="<?php echo FileService::getUrl($file); ?>" target="_blank"><?php echo basename($file->filename); ?></a>
    <?php }
}
