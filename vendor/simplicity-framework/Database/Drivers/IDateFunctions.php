<?php

namespace Sim\Database\Drivers;


interface IDateFunctions
{
    const DATE_UNIT_MICROSECOND = 'MICROSECOND';
    const DATE_UNIT_SECOND = 'SECOND';
    const DATE_UNIT_MINUTE = 'MINUTE';
    const DATE_UNIT_HOUR = 'HOUR';
    const DATE_UNIT_DAY = 'DAY';
    const DATE_UNIT_WEEK = 'WEEK';
    const DATE_UNIT_MONTH = 'MONTH';
    const DATE_UNIT_QUARTER = 'QUARTER';
    const DATE_UNIT_YEAR = 'YEAR';
    const DATE_UNIT_SECOND_MICROSECOND = 'SECOND_MICROSECOND';
    const DATE_UNIT_MINUTE_MICROSECOND = 'MINUTE_MICROSECOND';
    const DATE_UNIT_MINUTE_SECOND = 'MINUTE_SECOND';
    const DATE_UNIT_HOUR_MICROSECOND = 'HOUR_MICROSECOND';
    const DATE_UNIT_HOUR_SECOND = 'HOUR_SECOND';
    const DATE_UNIT_HOUR_MINUTE = 'HOUR_MINUTE';
    const DATE_UNIT_DAY_MICROSECOND = 'DAY_MICROSECOND';
    const DATE_UNIT_DAY_SECOND = 'DAY_SECOND';
    const DATE_UNIT_DAY_MINUTE = 'DAY_MINUTE';
    const DATE_UNIT_DAY_HOUR = 'DAY_HOUR';
    const DATE_UNIT_YEAR_MONTH = 'YEAR_MONTH';

    /**
     * @param $date
     * @param $value
     * @param string $unit
     * @return mixed - INTERVAL $value $unit
     */
    public function addDate($date, $value, string $unit = self::DATE_UNIT_DAY);

    /**
     * @param $time
     * @param $value
     * @return mixed
     */
    public function addTime($time, $value);

    /**
     * @return mixed
     */
    public function curDate();

    /**
     * @return mixed
     */
    public function currentDate();

    /**
     * @return mixed
     */
    public function currentTime();

    /**
     * @return mixed
     */
    public function currentTimestamp();

    /**
     * @return mixed
     */
    public function curTime();

    /**
     * @param $expression
     * @return mixed
     */
    public function date($expression);

    /**
     * @param $date1
     * @param $date2
     * @return mixed - gives numbers of days different between $date1 and  $date2
     */
    public function dateDiff($date1, $date2);

    /**
     * @param $date
     * @param $value
     * @param string $unit
     * @return mixed - INTERVAL $value $unit
     */
    public function dateAdd($date, $value, string $unit = self::DATE_UNIT_DAY);

    /**
     * @see https://www.w3schools.com/sql/func_mysql_date_format.asp
     *
     * @param $date
     * @param $format
     * @return mixed
     */
    public function dateFormat($date, $format);

    /**
     * @param $date
     * @param $value
     * @param string $unit
     * @return mixed - INTERVAL $value $unit
     */
    public function dateSub($date, $value, string $unit = self::DATE_UNIT_DAY);

    /**
     * @param $date
     * @return mixed
     */
    public function day($date);

    /**
     * @param $date
     * @return mixed
     */
    public function dayName($date);

    /**
     * @param $date
     * @return mixed
     */
    public function dayOfMonth($date);

    /**
     * @param $date
     * @return mixed
     */
    public function dayOfWeek($date);

    /**
     * @param $date
     * @return mixed
     */
    public function dayOfYear($date);

    /**
     * @param $unit
     * @param $date
     * @return mixed - $unit FROM $date
     */
    public function extract($unit, $date);

    /**
     * @param $day
     * @return mixed
     */
    public function fromDays($day);

    /**
     * @param $date
     * @return mixed
     */
    public function hour($date);

    /**
     * @param $date
     * @return mixed
     */
    public function lastDay($date);

    /**
     * @return mixed
     */
    public function localTime();

    /**
     * @return mixed
     */
    public function localTimestamp();

    /**
     * @param $year
     * @param $day
     * @return mixed
     */
    public function makeDate($year, $day);

    /**
     * @param $hour
     * @param $minute
     * @param $second
     * @return mixed
     */
    public function makeTime($hour, $minute, $second);

    /**
     * @param $date
     * @return mixed
     */
    public function microsecond($date);

    /**
     * @param $date
     * @return mixed
     */
    public function minute($date);

    /**
     * @param $date
     * @return mixed
     */
    public function month($date);

    /**
     * @param $date
     * @return mixed
     */
    public function monthName($date);

    /**
     * @return mixed
     */
    public function now();

    /**
     * @param $period - YYMM or YYYYMM
     * @param $month
     * @return mixed
     */
    public function periodAdd($period, $month);

    /**
     * @param $period1 - YYMM or YYYYMM
     * @param $period2 - YYMM or YYYYMM
     * @return mixed
     */
    public function periodDiff($period1, $period2);

    /**
     * January-March returns 1
     * April-June returns 2
     * July-Sep returns 3
     * Oct-Dec returns 4
     *
     * @param $date
     * @return mixed
     */
    public function quarter($date);

    /**
     * @param $date
     * @return mixed
     */
    public function second($date);

    /**
     * @param $seconds
     * @return mixed
     */
    public function secToTime($seconds);

    /**
     * @param $date
     * @param $format
     * @return mixed
     */
    public function strToDate($date, $format);

    /**
     * @param $date
     * @param $value
     * @param string $unit
     * @return mixed - INTERVAL $value $unit
     */
    public function subDate($date, $value, string $unit = self::DATE_UNIT_DAY);

    /**
     * @param $date
     * @param $time_interval
     * @return mixed
     */
    public function subTime($date, $time_interval);

    /**
     * @return mixed
     */
    public function sysDate();

    /**
     * @param $expression
     * @return mixed
     */
    public function time($expression);

    /**
     * @see https://www.w3schools.com/sql/func_mysql_time_format.asp
     *
     * @param $time
     * @param $format
     * @return mixed
     */
    public function timeFormat($time, $format);

    /**
     * @param $time
     * @return mixed
     */
    public function timeToSec($time);

    /**
     * Note: time1 and time2 should be in the same format, and the calculation is time1 - time2.
     *
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function timeDiff($date1, $date2);

    /**
     * @param $expression
     * @param null $time
     * @return mixed
     */
    public function timestamp($expression, $time = null);

    /**
     * @param $date
     * @return mixed
     */
    public function toDays($date);

    /**
     * @param $date
     * @return mixed
     */
    public function week($date);

    /**
     * Note: 0 = Monday, 1 = Tuesday, 2 = Wednesday, 3 = Thursday, 4 = Friday, 5 = Saturday, 6 = Sunday.
     *
     * @param $date
     * @return mixed
     */
    public function weekDay($date);

    /**
     * @param $date
     * @return mixed
     */
    public function weekOfYear($date);

    /**
     * @param $date
     * @return mixed
     */
    public function year($date);

    /**
     * @param $date
     * @return mixed
     */
    public function yearWeek($date);
}