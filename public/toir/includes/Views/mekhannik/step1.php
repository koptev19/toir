<h2 class='text-center pb-4'>Установка механика цеха <?php echo $this->workshop->NAME; ?></h2>

<div class='m-auto' style="width:300px;">
<form method="get" action="mekhannik.php">
<input type="hidden" name="step" value='2'>
<input type="hidden" name="save" value='1'>
<input type="hidden" name="workshop" value='<?php echo $this->workshop->ID; ?>'>

<h4 class='text-center pb-4'><?php echo $mekhannik['NAME'] . ' ' . $mekhannik['LAST_NAME']; ?></h4>

<?php

   $GLOBALS["APPLICATION"]->IncludeComponent('bitrix:intranet.user.selector.new', array(     
       'NAME' => "OWNER",       
       "MULTIPLE" => "N",     
       'INPUT_NAME' => "mekhannik",    
       'INPUT_NAME_STRING' => "OWNER_STRING",   
       'INPUT_NAME_SUSPICIOUS' => "OWNER_SUSPICIOUS",     
       'TEXTAREA_MIN_HEIGHT' => 30,    
       'TEXTAREA_MAX_HEIGHT' => 30,
   )  
       );

?>

</div>
<div class='mt-4 text-center'><button type="submit" class="btn btn-primary">Сохранить</button></div>
</form>
