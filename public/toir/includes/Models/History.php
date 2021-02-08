<?php

class History extends ToirModel
{

    public const SOURCE_PLAN_DATE = 'Планирование';
    public const SOURCE_REPORT_DATE = 'Отчет';
    public const SOURCE_CHANGE_DATE = 'Перенос дат';
    public const SOURCE_CRASH = 'Авария';
    public const SOURCE_SERVICE = 'Заявка на ремонт';
    public const SOURCE_ADD_OPERATION = 'Добавление операции';
	public const SOURCE_DOWNTIME = 'Простой';
    public const SOURCE_TMC = 'Списание ТМЦ';

    public $table = 'histories';

    protected $modify = [
    ];

    /**
     * @return Plan|null
     */
    public function plan(): ?Plan
    {
        return Plan::find($this->PLAN_ID);
    }

    /**
     * @return Operation|null
     */
    public function operation(): ?Operation
    {
        return Operation::find($this->OPERATION_ID);
    }

    /**
     * @return UserToir|null
     */
    public function author(): ?UserToir
    {
        return UserToir::find($this->author_id);
    }

    /**
     * @return string
     */
    public function sourceAndLink(): string
    {
        $result = $this->SOURCE;
        if (substr($result, 0, strlen(History::SOURCE_SERVICE)) == History::SOURCE_SERVICE) {
            $id = intval(substr($result, strpos($result, ':') + 1));
            $result = '<a href="service_request.php?selected_id=' . $id . '" target=_blank>' . History::SOURCE_SERVICE . ': ' . $id . '</a>';
        }
        if (substr($result, 0, strlen(History::SOURCE_CRASH)) == History::SOURCE_CRASH) {
            $id = intval(substr($result, strpos($result, ':') + 1));
            $crash = Crash::find($id);
            $result = '<a href="crashes.php?crash=' . $id . '" target=_blank>' . History::SOURCE_CRASH . ': ' . ($crash ? date("d.m.Y", strtotime($crash->DATE)) : '') . '</a>';
        }
        return $result;
    }

}