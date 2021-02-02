<div class="m-auto pt-5" style="width:400px;">
<?php if(count($workshops) > 0) { ?>
    <h5 class="text-center">Выберите цех</h5>
    <ul class="list-group mt-4">
    <?php foreach($workshops as $workshop) { ?>
        <li class="list-group-item"><a href="index.php?workshop=<?php echo $workshop->ID; ?>"><?php echo $workshop->NAME; ?></a></li>
    <?php } ?>
    </ul>
<?php } else { ?>
    К сожалению, Вы не подключены к системе
<?php } ?>
</div>