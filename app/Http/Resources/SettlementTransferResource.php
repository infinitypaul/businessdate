<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SettlementTransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $initialDate = Carbon::createFromDate($request->initialDate,'Europe/Madrid');
        $businessDate = $this->addDaysTo($initialDate, $request->delay);
        return [
            'ok' => true,
            'initialQuery' => [
                'initialDate' => $request->initialDate,
                'delay' => $request->delay
            ],
            'results' => [
                'businessDate' => $businessDate,
                'totalDays' => $this->totalDaysBetween($initialDate, $businessDate),
                'holidayDays' => $this->holidayCount,
                'weekendDays' => $this->weekendsCount
            ]
        ];
    }
}
