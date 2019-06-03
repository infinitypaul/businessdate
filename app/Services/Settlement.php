<?php


namespace App\Services;


use Carbon\Carbon;

class Settlement
{
    protected $weekendDays = [
        Carbon::SATURDAY+1, Carbon::SUNDAY+1
    ];

    protected $holidays = [];

    protected $closedDays = [];

    public $weekendsCount = 0;
    public $holidayCount = 0;

    /**
     * @param array $weekendDays
     *
     * @return \App\Services\Settlement
     */

    public function setWeekendDays(array $weekendDays): self
    {
        $this->weekendDays = array_map(function($day) {
            return $day + 1;
        }, $weekendDays);
        return $this;
    }

    /**
     * @param \Carbon\Carbon $date
     *
     * @return \App\Services\Settlement
     */
    public function addHoliday(Carbon $date): self
    {
        if(!$this->isHoliday($date)) {
            $this->holidays[] = $date->format("Ymd");
        }
        return $this;
    }

    /**
     * @param \Carbon\Carbon $date
     *
     * @return bool
     */
    public function isHoliday(Carbon $date): bool
    {
        if(array_search($date->format("Ymd"), $this->holidays) !== false){
            $this->holidayCount++;
            return true;
        }
            return false;
    }

    /**
     * @param array $dates
     *
     * @return \App\Services\Settlement
     */
    public function addHolidays(array $dates): self
    {
        foreach($dates as $date) {
            $this->addHoliday($date);
        }
        return $this;
    }



    /**
     * @param \Carbon\Carbon $date
     *
     * @return \App\Services\Settlement
     */
    public function removeHoliday(Carbon $date): self
    {
        if($k = array_search($date->format("Ymd"),$this->holidays) !== false) {
            array_splice($this->holidays, $k, 1);
        }
        return $this;
    }



    /**
     * @param \Carbon\Carbon $day
     *
     * @return bool
     */
    public function isWeekendDay(Carbon $day): bool
    {

        if(array_search($day->dayOfWeek + 1, $this->weekendDays) !== false){
            $this->weekendsCount++;
            return true;
        }
            return false;
    }



    /**
     * @param \Carbon\Carbon $from
     * @param \Carbon\Carbon $to
     *
     * @return \App\Services\Settlement
     */
    public function addClosedPeriod(Carbon $from, Carbon $to): self
    {
        for($date = $from->copy(); $date <= $to; ) {
            if(!$this->isClosed($date)) {
                $this->closedDays[] = $date->format("Ymd");
            }
            $date = $date->addDay();
        }
        return $this;
    }



    /**
     * @param \Carbon\Carbon $date
     *
     * @return bool
     */
    public function isClosed(Carbon $date): bool
    {
        return array_search($date->format("Ymd"), $this->closedDays) !== false;
    }


    /**
     * @param \Carbon\Carbon $date
     *
     * @return \App\Services\Settlement
     */
    public function removeClosedDay(Carbon $date): self
    {
        if($k = array_search($date->format("Ymd"), $this->closedDays) !== false) {
            array_splice($this->closedDays, $k, 1);
        }
        return $this;
    }



    /**
     * @param \Carbon\Carbon $from
     * @param \Carbon\Carbon $to
     *
     * @return int
     */
    public function daysBetween(Carbon $from, Carbon $to): int
    {
        return $from->diffInDaysFiltered(function(Carbon $day) {
            return $this->isOpenedDay($day);
        }, $to);
    }


    public function totalDaysBetween(Carbon $from, Carbon $to){
        return $from->diffInDays($to);
    }



    /**
     * @param \Carbon\Carbon $date
     *
     * @return bool
     */
    public function isOpenedDay(Carbon $date): bool
    {
        return !$this->isWeekendDay($date)
            && !$this->isHoliday($date)
            && !$this->isClosed($date);
    }



    /**
     * @param \Carbon\Carbon $date
     * @param int $days
     *
     * @return \Carbon\Carbon
     */
    public function subDaysFrom(Carbon $date, int $days): Carbon
    {
        $resultDate = $date->copy();
        while($days > 0) {
            if($this->isOpenedDay($resultDate->subDay())) {
                $days--;
            }
        }
        return $resultDate;
    }




    /**
     * @param \Carbon\Carbon $date
     * @param int $days
     *
     * @return \Carbon\Carbon
     */
    public function addDaysTo(Carbon $date, int $days): Carbon
    {
        $resultDate = $date->copy();
        //dd($resultDate);
        while($days > 0) {
            if($this->isOpenedDay($resultDate)) {
                $days--;
            }
            $resultDate->addDay();

        }
        return $resultDate->subDay();
    }



    /**
     * @return array
     */
    public function getClosedDays()
    {
        return array_map(function($date) {
            return Carbon::createFromFormat('Ymd', $date)->startOfDay();
        }, $this->closedDays);
    }



    /**
     * @return array
     */
    public function getHolidays()
    {
        return array_map(function($date) {
            return Carbon::createFromFormat('Ymd', $date)->startOfDay();
        }, $this->holidays);
    }

}
