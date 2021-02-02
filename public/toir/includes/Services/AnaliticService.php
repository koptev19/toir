<?php

require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/Analitic/PublicDataTrait.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/Analitic/Table1Trait.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/Analitic/Table2_1Trait.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/Analitic/Table2_2Trait.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/Analitic/Table3Trait.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/Analitic/Table4_1Trait.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/Analitic/Table4_2Trait.php");
require_once($_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Services/Analitic/Table5Trait.php");

class AnaliticService
{
    use PublicDataTrait; 
    use Table1Trait;
    use Table2_1Trait;
    use Table2_2Trait;
    use Table3Trait;
    use Table4_1Trait;
    use Table4_2Trait;
    use Table5Trait;

    /**
     * @var string
     */
    private $dateFrom;

    /**
     * @var string
     */
    private $dateTo;

    public function __construct(string $dateFrom, string $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }



}