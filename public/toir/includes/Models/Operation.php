<?php

class Operation extends ToirModel
{
    public const REASON_INSTRUCTION = 'instruction';
    public const REASON_VIEW = 'view';
    public const REASON_CRASH = 'crash';
	public const REASON_DOWNTIME = 'downtime';

    public const SOURCE_GROUP_PLAN_DATE = 'plan_date';
    public const SOURCE_GROUP_REPORT_DATE = 'report_date';
    public const SOURCE_GROUP_INDEX = 'index';
    public const SOURCE_GROUP_SERVICE_REQUIEST = 'service_requiest';
    public const SOURCE_GROUP_CRASH = 'crash';
	public const SOURCE_GROUP_DOWNTIME = 'downtime';

    public const TYPE_REPAIR = 'repair';
    public const TYPE_REPLACEMENT = 'replacement';
    public const TYPE_TO = 'to';
    public const TYPE_OTHER = 'other';

    private static $types = [
        self::TYPE_REPAIR => 'Ремонт',
        self::TYPE_REPLACEMENT => 'Замена',
        self::TYPE_TO => 'ТО',
        self::TYPE_OTHER => 'Прочие работы',
    ];

    public $table = 'operations';

    protected $modify = [];

    /**
     * @return array
     */
    public static function getTypes(): array
    {
        return self::$types;
    }

    /**
     * @param string $type
     * @return array
     */
    public static function getVerbalType(string $type): string
    {
        return self::$types[$type];
    }

    /**
     * @param string $reason
     * @return string
     */
    public static function verbalReason(string $reason): string
    {
        switch ($reason) {
            case self::REASON_INSTRUCTION:
                return "Инструкция по эксплуатации оборудования";
                break;
            case self::REASON_VIEW:
                return "Осмотр оборудования";
                break;
            case self::REASON_CRASH:
                return "Авария";
                break;
			case self::REASON_DOWNTIME:
                return "Простой";
                break;
            default:
                return "";
        }
    }

    /**
     * @return array
     */
    public static function reasons(): array
    {
        return [
            self::REASON_INSTRUCTION => self::verbalReason(self::REASON_INSTRUCTION),
            self::REASON_VIEW => self::verbalReason(self::REASON_VIEW),
            self::REASON_CRASH => self::verbalReason(self::REASON_CRASH),
        ];
    }

    /**
     * @return Plan|null
     */
    public function plan(): ?Plan
    {
        return Plan::find($this->PLAN_ID);
    }

    /**
     * @return Work|null
     */
    public function work(): ?Work
    {
        return Work::find($this->WORK_ID);
    }

    /**
     * @return Crash|null
     */
    public function crash(): ?Crash
    {
        return Crash::find($this->CRASH_ID);
    }

    /**
     * @return ToirModelBuilder
     */
    public function histories(): ToirModelBuilder
    {
        return History::filter(['OPERATION_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function worktimes(): ToirModelBuilder
    {
        return Worktime::filter(['OPERATION_ID' => $this->ID]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function planWorktimes(): ToirModelBuilder
    {
        return $this->worktimes()->setFilter(['action' => Worktime::ACTION_PLAN]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function reportWorktimes(): ToirModelBuilder
    {
        return $this->worktimes()->setFilter(['action' => Worktime::ACTION_REPORT]);
    }

    /**
     * @return ToirModelBuilder
     */
    public function copies(): ToirModelBuilder
    {
        return Operation::filter(['SOURCE_OPERATION_ID' => $this->ID]);
    }

    /**
     * @return DateProcess|null
     */
    public function dateProcess(): ?DateProcess
    {
        return DateProcess::find($this->DATE_PROCESS_ID);
    }

    /**
     * @param string|null $comment
     *
     * @return void
     */
    public function updateCommentNoResult(?string $comment)
    {
        $this->COMMENT_NO_RESULT = (string)$comment;
        $this->save();

        if($plan = $this->plan()) {
            $plan->COMMENT_NO_RESULT = $comment;
            $plan->save();
        }
    }

    /**
     * @return int
     */
    public function getLate(): int
    {
        $late = $this->LAST_DATE_FROM_CHECKLIST 
            ? 0
            : floor((time() - strtotime($this->PLANNED_DATE)) / (60 * 60 * 24));
        return max($late, 0);
    }

}