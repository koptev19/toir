<form action="crashes.php" method="post">
<input type="hidden" name="crashId" value="<?php echo $crash->ID?>">
<input type="hidden" name="workshop" value="<?php echo $crash->WORKSHOP_ID?>">
<input type="hidden" name="save_description" value="1">
<div class='border mb-4'>
<?php
global $APPLICATION;

$APPLICATION->IncludeComponent("bitrix:fileman.light_editor","",Array(
    "CONTENT" => html_entity_decode($crash->_DESCRIPTION['TEXT']),
    "INPUT_NAME" => $name,
    "INPUT_ID" => $name,
    "WIDTH" => "100%",
    "HEIGHT" => "300px",
    "RESIZABLE" => "Y",
    "AUTO_RESIZE" => "Y",
    "VIDEO_ALLOW_VIDEO" => "Y",
    "VIDEO_MAX_WIDTH" => "640",
    "VIDEO_MAX_HEIGHT" => "480",
    "VIDEO_BUFFER" => "20",
    "VIDEO_LOGO" => "",
    "VIDEO_WMODE" => "transparent",
    "VIDEO_WINDOWLESS" => "Y",
    "VIDEO_SKIN" => "/bitrix/components/bitrix/player/mediaplayer/skins/bitrix.swf",
    "USE_FILE_DIALOGS" => "Y",
    "ID" => "",	
    "JS_OBJ_NAME" => ""
    )
); ?>
</div>
<input type="submit" class='btn btn-primary' value='Сохранить'>
</form>