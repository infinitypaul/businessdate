<?php

namespace Tests\Unit;

use App\Services\Settlement;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettlementTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    function test_we_can_get_holidays_as_an_array()
    {
        $date = new Settlement();
        $date->addHoliday(Carbon::createFromDate(2018, 11, 15));
        $date->addHoliday(Carbon::createFromDate(2018, 11, 10));
        $this->assertEquals(Carbon::createFromDate(2018, 11, 15)->startOfDay(), $date->getHolidays()[0]);
        $this->assertEquals(Carbon::createFromDate(2018, 11, 10)->startOfDay(), $date->getHolidays()[1]);
    }

    function test_we_can_account_for_holidays()
    {
        $date = new Settlement();

        $date->addHoliday(Carbon::createFromDate(2018, 11, 12));

        $this->assertEquals("2018-11-15", $date->addDaysTo(
            Carbon::createFromDate(2018, 11, 10),
            3
        )->format("Y-m-d"));

        $this->assertEquals("2018-11-20", $date->addDaysTo(
            Carbon::createFromDate(2018, 11, 15),
            4
        )->format("Y-m-d"));
    }

    function test_we_can_add_business_days_to_a_date()
    {
        $date = new Settlement();
        $this->assertEquals("2018-05-15", $date->addDaysTo(
            Carbon::createFromDate(2018, 5, 14), // Monday
            2
        )->format("Y-m-d"));
    }

    function test_we_can_get_json_as_response(){
        $response  = $this->get(route('getBusinessDateWithDelay', ['2018-11-10T10:10:10Z',3]));
        $response->assertStatus(200)
            ->assertExactJson([
                'ok' => true,
                'initialQuery' => [
                    "initialDate" => "2018-11-10T10:10:10Z",
                    "delay" => "3"
                ],
                "results" => [
                    "businessDate"=> "2018-11-15T10:10:10.000000Z",
                    "totalDays"=> 5,
                    "holidayDays"=> 1,
                    "weekendDays"=> 2
                ]
            ]);
    }
    function test_we_can_post_to_url(){
        $response = $this->json('POST', route('postBusinessDateWithDelay'),
            [
                'initialDate' => '2018-11-10T10:10:10Z',
                'delay' => 3
            ]);
        //dd($response->dump());
        $response->assertStatus(200)
            ->assertExactJson([
                'ok' => true,
                'initialQuery' => [
                    "initialDate" => "2018-11-10T10:10:10Z",
                    "delay" => 3
                ],
                "results" => [
                    "businessDate"=> "2018-11-15T10:10:10.000000Z",
                    "totalDays"=> 5,
                    "holidayDays"=> 1,
                    "weekendDays"=> 2
                ]
            ]);
    }

}
