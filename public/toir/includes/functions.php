<?php

/**
 * @param mixed $var
 * @param bool $printPre = true
 * 
 * @return void
 */
function dump($var, bool $printPre = true)
{
    if($printPre) {
        echo "<pre>";
    }
    if (is_null($var)) {
        echo "null";
    } elseif (is_string($var)) {
        echo '"' . htmlspecialchars($var) . '"';
    } elseif (is_array($var)) {
        echo "Array (<br>";
        foreach($var as $key => $item) {
            echo "<div style='padding-left:25px;'>";
            echo htmlspecialchars($key) . ' => ';
            dump($item, false);
            echo "</div>";
        }
        echo ")<br><br>";
    } elseif (is_object($var)) {
        print_r($var);
    } elseif(is_bool($var)) {
        echo $var ? "true" : "false";
    } else {
        echo $var;
    }
    if($printPre) {
        echo "</pre>";
    }
}

/**
 * @param mixed $var
 * 
 * @return void
 */
function dd($var)
{
    dump($var);
    die();
}

/**
 * @return void
 */
function dumpQueries()
{
    $mysql = MysqlConnecter::getInstance();
    dump($mysql->queries);
}

/**
 * @param string|int $date
 * @return string
 */
function d($date): string
{
    $time = is_int($date) ? $date : strtotime($date);

    return $time ? date('d.m.Y', $time) : '';
}

/**
 * @param int|string $num
 * 
 * @return string
 */
function monthName($num): string
{
    $monthname = [
        1 => "Январь",
        2 => "Февраль",
        3 => "Март",
        4 => "Апрель",
        5 => "Май", 
        6 => "Июнь",
        7 => "Июль",
        8 => "Август",
        9 => "Сентябрь",
        10 => "Октябрь",
        11 => "Ноябрь",
        12 => "Декабрь",
    ];
    return $monthname[intval($num)];
}

/**
 * @param int $day
 * @param int $month
 * @param int $year
 * 
 * @return bool
 */
function isWeekend(int $day, int $month, int $year): bool
{
    $time = mktime(0, 0, 0, $month, $day, $year);
    $weekday = date('w', $time);
    return ($weekday == 0 || $weekday == 6);
}

/**
 * @param int|string $day
 * @param int|string $month
 * @param int|string $year
 * 
 * @return string
 */
function classForHistoryDate($day, $month, $year): string
{
    $d = (int)$day;
    $m = (int)$month;
    $y = (int)$year;

    $day = ($d < 10 ? '0' : '') . $d;
    $month = ($m < 10 ? '0' : '') . $m;

    return $year . '-' . $month . '-' . $day > date('Y-m-d') 
        ? 'table-secondary' 
        : (isWeekend($d, $m, $y) 
            ? 'table-danger' 
            : ''
        );
}

/**
 * @return array
 */
function currentMonth(): array
{
    return [
        'Y' => intval(date('Y')),
        'm' => intval(date('n'))
    ];
}

/**
 * @param array|null $date
 * @return array
 */
function nextMonth(?array $date = null): array
{
    if(empty($date)) {
        $date = currentMonth();
    }
    return [
        'Y' => $date['m'] == 12 ? $date['Y'] + 1 : $date['Y'],
        'm' => $date['m'] == 12 ? 1 : $date['m'] + 1
    ];
}

/**
 * @param array|null $date
 * @return array
 */
function next2Month(?array $date = null): array
{
    if(empty($date)) {
        $date = currentMonth();
    }
    return nextMonth(nextMonth($date));
}

/**
 * @param array|null $date
 * @return array
 */
function prevMonth(?array $date = null): array
{
    if(empty($date)) {
        $date = currentMonth();
    }
    return [
        'Y' => $date['m'] == 1 ? $date['Y'] - 1 : $date['Y'],
        'm' => $date['m'] == 1 ? 12 : $date['m'] - 1
    ];
}


