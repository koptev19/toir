<?php

class Stop extends ToirModel
{

    public const STAGE_NEW = 1;
    public const STAGE_PLAN_DONE = 10;
    public const STAGE_REPORT_DONE = 20;

    public $table = 'stops';

    protected $modify = [
        'LINE' => 'int',
        'LINE_NEW' => 'int',
        'WORKSHOP' => 'int',
        'TASK' => 'int',
        'PRE_TASK' => 'int',
        'REPORT_TASK' => 'int',
        'STAGE' => 'int',
    ];

    /**
     * @param int $lineId
     * @param int $year
     * @param int $month
     * 
     * @return array
     */
    public static function getByLineInMonth(int $lineId, int $year, int $month): array
    {
        $filter = [
            'LINE_ID' => $lineId,
            '>DATE' => date("Y-m-d H:i:s", mktime(0, 0, 0, $month, 1, $year) - 1),
            '<DATE' => date("Y-m-d H:i:s", mktime(0, 0, 0, $month + 1, 1, $year)),
        ];

        $stops = self::filter($filter)
            ->orderBy('DATE')
            ->get();

        $result = [];
        foreach ($stops as $stop) {
            $result[$stop->DATE] = $stop;
        }

        return $result;
    }

    /**
     * @param int $lineId
     * @param int $date
     * 
     * @return Stop|null
     */
    public static function getByLineDate(int $lineId, string $date): ?Stop
    {
        $date = date('Y-m-d', strtotime($date));

        return self::filter([
                'LINE_ID' => $lineId,
                'DATE' => $date,
            ])
            ->first();
    }

    /**
     * @return Crash|null
     */
    public function crash(): ?Crash
    {
        return Crash::find($this->CRASH_ID);
    }
}

