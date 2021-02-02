<?php
$limit = (int)$_REQUEST['limit'] > 0 ? (int)$_REQUEST['limit'] : 50;
$page = (int)$_REQUEST['page'] > 0 ? (int)$_REQUEST['page'] : 1;
?>
<div class='row'>
    <div class='col-10 text-center'>
        <?php if ($page > 1)  {?>
            <a href="#" onclick="changePage(<?php echo $page - 1; ?>); return false;"><img src='./images/chevron-left.svg'> Предыдущая</a>
        <?php } ?>

        <?php for($i = max(1, $page - 3); $i <= min($page + 3, $maxPage); $i++) {?>
            <?php if($i == $page) { ?>
                <span class='font-weight-bold ml-2'><?php echo $i; ?></a>
            <?php } else { ?>
                <a href="#" class='ml-2' onclick="changePage(<?php echo $i; ?>); return false;"><?php echo $i; ?></a>
            <?php } ?>
        <?php } ?>

        <?php if ($page < $maxPage)  {?>
            <a href="#" onclick="changePage(<?php echo $page + 1; ?>); return false;" class='ml-2'>Следующая <img src='./images/chevron-right.svg'></a>
        <?php } ?>
    </div>
    <div class="col-2 text-right">На странице: <select class="p-2" onchange="changeLimit(this);">
        <option value="10" <?php if($limit == 10) echo "selected"; ?>>10</option>
        <option value="20" <?php if($limit == 20) echo "selected"; ?>>20</option>
        <option value="50" <?php if($limit == 50) echo "selected"; ?>>50</option>
        <option value="100" <?php if($limit == 100) echo "selected"; ?>>100</option>
        <option value="500" <?php if($limit == 500) echo "selected"; ?>>500</option>
    </select></div>
</div>

<script>
function changeLimit(s) {
    let url = new URL(document.location.href);
    url.searchParams.set('limit', $(s).val());
    url.searchParams.set('page', 1);
    document.location.href = url.toString();
}

function changePage(p) {
    let url = new URL(document.location.href);
    url.searchParams.set('page', p);
    document.location.href = url.toString();
}
</script>
