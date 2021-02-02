<?php
    global $INTRANET_TOOLBAR;

    $arButtonAdd = [
        'TEXT' => "Добавить плановую операцию",
        'TITLE' => "Добавить плановую операцию",
        'ICON' => '',
        'HREF' => 'add_plan.php' . $this->getUrlStep1(),
        'SORT' => 10,
        
    ];
    $INTRANET_TOOLBAR->addButton($arButtonAdd);

    ?>