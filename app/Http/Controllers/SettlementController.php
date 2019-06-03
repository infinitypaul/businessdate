<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettlementTransferResource;
use App\Services\Settlement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public $transfer;

    public function __construct()
    {
        $this->transfer  = new Settlement();
        $this->transfer->addHolidays([
            Carbon::createFromDate(2018, 11, 12),
            Carbon::createFromDate(2018,12,25),
            Carbon::createFromDate(2019,1,1),
            Carbon::createFromDate(2019,1,21)
        ]);
    }

    public function transfer(){
        return new SettlementTransferResource($this->transfer);
    }

    public function isBusinessDay()  {
            return response()->json([
                'initialDate' => \request('date'),
                'result' => [
                    'response' => $this->transfer->isOpenedDay(Carbon::createFromDate(\request('date')))
                ]
            ]);
    }
}
