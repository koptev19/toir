<script type="text/javascript" src="/bitrix/components/bitrix/disk.uf.file/templates/.default/script.js"></script>
<script type="text/javascript">BX.loadCSS('/bitrix/components/bitrix/disk.uf.file/templates/.default/style.min.css');</script>
<script type="text/javascript" src='/bitrix/components/bitrix/intranet.user.selector.new/templates/.default/users.js'></script>
<script type="text/javascript">BX.loadCSS('/bitrix/components/bitrix/intranet.user.selector.new/templates/.default/style.css');</script>
<form>
	<div class="error alert alert-danger" role="alert" style="display:none;"></div>
	<input type=hidden name="ID" value="<?php echo $node->ID; ?>">
	<input type=hidden name="PARENT_ID" value="<?php echo $node->PARENTID ; ?>">
	<div class='mb-4'>
		<span class='h5'><?php echo $node->NAME; ?></span>
	</div>

	<div class="row mb-3">
		<div class='col-2'>Тип:</div>
		<div class="col-10">
			<select class='form-control form-select' name="TYPE">
				<?php foreach($types as $key => $name) { ?>
					<option value='<? echo $key; ?>' <?php if($name == $node->TYPE) echo "selected"; ?>><?php echo $name; ?></option>
				<?php } ?>	
			</select>
		</div>
	</div>
	<div class="row mb-3">
		<div class='col-2'>Наименование:</div>
		<div class="col-10"><input type="text" name="NAME" value="<?php echo $node->NAME;  ?>" class='form-control' required></div>
	</div>
	<div class="row mb-3">
		<div class='col-2'>Начальник:</div>
		<div class="col-10">
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
	</div>
	
	<div class="row mb-3">
		<div class='col-2'>Файлы:</div>
		<div class="col-10">
			<?$GLOBALS["APPLICATION"]->includeComponent(
					'bitrix:disk.uf.file',
					'.default',
					[
						'EDIT' => 'Y',
						'PARAMS' => ["HIDE_CHECKBOX_ALLOW_EDIT"=>true],
						'RESULT' => $result,
						'DISABLE_LOCAL_EDIT' => $map['DISABLE_LOCAL_EDIT'],
						'DISABLE_CREATING_FILE_BY_CLOUD' => $map['DISABLE_CREATING_FILE_BY_CLOUD'],
					],
					$component,
					['HIDE_ICONS' => 'Y']
				);
			?>	
		</div>
	</div>
	
	<div class="row mb-3">
		<div class='col-2'>Описание:</div>
		<div class="col-10">
			<?$GLOBALS["APPLICATION"]->IncludeComponent("bitrix:fileman.light_editor","",Array(
			"CONTENT" => "",
			"INPUT_NAME" => "",
			"INPUT_ID" => "",
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
			);?>	
		</div>
	</div>	

	<div class='mt-4'>
		<a onClick="updateNode(this); return false" class='btn btn-primary'>Сохранить</a>
		<a onClick="showNode(<?php echo $node->ID; ?>); return false" class='btn btn-outline-secondary ml-5'>Отмена</a>
	</div>
</form>