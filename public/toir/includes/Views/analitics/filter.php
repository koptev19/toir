<div id='analitics-filter-fixed' class='p-3 bg-light d-none position-fixed' style="z-index:100; top:0px; right:80px; left:80px;">
</div>

<div id='analitics-filter' class="p-3">
    <form action="" method="GET">
        <input type="hidden" name="workshop" value="<?php echo $this->workshop->ID; ?>">
        <div class="row mb-4">
            <div class="col-2">
                <input type="date" name="date_from" class="form-control" value="<?php echo $this->dateFrom; ?>" onchange="this.form.submit();">                
            </div>
            <div class="col-2">
                <input type="date" name="date_to" class="form-control" value="<?php echo $this->dateTo; ?>" onchange="this.form.submit();">
            </div>
        </div>
    </form>
</div>


<script>
window.addEventListener('scroll', function() {
    if (pageYOffset > 210) {
        $('#analitics-filter-fixed').removeClass('d-none');
        $('#analitics-filter').addClass('invisible');
    } else {
        $('#analitics-filter-fixed').addClass('d-none');
        $('#analitics-filter').removeClass('invisible');
    }
});

$(document).ready(function() {
    $('#analitics-filter-fixed').html($('#analitics-filter').html());
});

</script>