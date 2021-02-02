<div class='text-center mt-5 mb-4'>
    <?php 
    $linkIsPlan .= '?workshop=' . $this->workshop->ID;
    $linkIsPlan .= $this->filter['SERVICE_ID'] ? '&SERVICE_ID=' . $this->filter['SERVICE_ID'] : '';
    $linkIsPlan .= $this->filter['line'] ? '&filter_line=' . $this->filter['line'] : '';
    $linkIsPlan .= $this->filter['mechanism'] ? '&filter_mechanism=' . $this->filter['mechanism'] : '';
    $linkIsPlan .= $this->filter['name'] ? '&filter_name=' . $this->filter['name'] : '';
    $linkIsPlan .= '&year=' . $this->year;
    $linkIsPlan .= '&month=' . $this->month;
    ?>
    <a href="<?php echo $linkIsPlan; ?>&table2=plan" id='table2-link1' <?php if($this->table2 == 'plan') echo 'class="h4 text-dark"'; ?> onclick="setTable2Cookie(<?php echo $this->workshop->ID; ?>, 'show')">Плановые операции</a>
    /
    <a href="<?php echo $linkIsPlan; ?>&table2=noplan" id='table2-link2' <?php if($this->table2 == 'noplan') echo 'class="h4 text-dark"'; ?> onclick="setTable2Cookie(<?php echo $this->workshop->ID; ?>, 'show')">Внеплановые операции</a>
    / 
    <a href="<?php echo $linkIsPlan; ?>&table2=notdone" id='table2-link3' <?php if($this->table2 == 'notdone') echo 'class="h4 text-dark"'; ?> onclick="setTable2Cookie(<?php echo $this->workshop->ID; ?>, 'show')">Невыполненные операции</a>
    
    <a href="#"  data-workshop='<?php echo $this->workshop->ID; ?>' onclick="toggleTable2(); return false;" class='ml-2 text-primary' id="link-toggle-table2">
        <?php echo ($_COOKIE["table2" . $this->workshop->ID] == "show") ? "Скрыть" : "Показать"; ?>
    </a>
</div>