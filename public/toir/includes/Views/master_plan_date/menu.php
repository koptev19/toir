<div class="table-warning mb-5 row">
    <?php if ($this->mode == 'plan') { ?>
        <div class="col-6 p-3">
            <h5>Планирование "План работ на день профилактики" <?php echo d($this->date); ?></h5>
        </div>
        <div class="col-6 text-right p-3">
            <a href="?mode=dates&step=1&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>" class="h5">Групповая смена дат у операций</a>
        </div>    
    <?php } else { ?>
        <div class="col-6 p-3">
            <h5>Групповая смена дат у операций</h5>
        </div>
        <div class="col-6 text-right p-3">
            <a href="?mode=plan&step=1&service=<?php echo $this->service->ID; ?>&date=<?php echo $this->date; ?>" class="h5">Планирование на <?php echo $this->date; ?></a>
        </div>    
    <?php } ?>
</div>