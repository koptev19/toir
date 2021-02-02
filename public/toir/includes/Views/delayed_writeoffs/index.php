<?php
    $this->view('_header', ['title' => "Журнал отложенных списаний ТМЦ"]);
?>

<h1 class="text-center mb-3">Журнал отложенных списаний ТМЦ</h1>

<div class="table-responsive mb-3">
<table class="table table-bordered table-sm table-hover" id="writeoffs">
    <thead>
        <tr class='text-center'>
            <th>Наименование оборудования</th>
            <th>Название регламентной операции</th>
            <th>Материально-ответственное лицо</th>
            <th>Дата операции</th>
            <th>Дата отчета</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($writeoffs as $writeoff) {
        $class = $writeoff->IS_DONE ? "table-secondary" : '';
		?>
        <tr>
            <td class="<?php echo $class; ?>"><?php echo $writeoff->operation->equipment->path(); ?></td>
            <td class="<?php echo $class; ?>"><?php echo $writeoff->operation->NAME; ?></td>
            <td class="<?php echo $class; ?>"><?php echo $writeoff->author->fullname; ?></td>
            <td class="<?php echo $class; ?> text-center"><?php echo d($writeoff->operation->PLANNED_DATE); ?></td>
            <td class="<?php echo $class; ?> text-center"><?php echo d($writeoff->created_at); ?></td>
            <td class="<?php echo $class; ?>  text-center">
                <?php if(!$writeoff->IS_DONE) { ?>
                    <a href="#" onClick="showDetailList(<?php echo $writeoff->operation->ID ?>); return false;">Списать ТМЦ</a><br><br>
                    <a onClick="return confirm('Оставить без списания?');" href="?done=<?php echo $writeoff->ID; ?>" class="">Без списания</a><br>
                    <a href="#" class="btn btn-primary" onclick="saveFile(<?php echo $writeoff->ID ?>);" style="display:none;" id="writeoff-operation-<?php echo $writeoff->operation->ID ?>">Списать</a>
                <?php } ?>
            </td>
            <td class='<?php echo $class; ?> text-left' id="op<?php echo $writeoff->operation->ID ?>"></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>

<?php $this->view('delayed_writeoffs/writeoff'); ?>

<?php $this->showFooter(); ?>