<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarViewController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private function getMonth($diff = 0) 
    {
        // Generating a calendar of the current month
        $first_day_of_the_month = date('w Y m d', mktime(0,0,0, date("m") + $diff, 1, date("Y")));
        $first_day_of_the_next_month = date('w Y m d', mktime(0,0,0, date("m") + $diff + 1, 1, date("Y")));
        $date = $first_day_of_the_month;
        $current_month_dates = [];
        $i = 1;
        while ($date !== $first_day_of_the_next_month) {
            $current_month_dates[] = $date;
            $date = date('w Y m d', mktime(0,0,0, date("m") + $diff, 1 + $i, date("Y")));
            $i += 1;
        }

        return $current_month_dates;
    }

    public function index($month = 0) // current month = 0
    {
        $current_month_dates = $this->getMonth($month);

        // Generating the final week of the previous month & the first week of the next month
        $i = 1;
        while ($i < 7) {
            if ( preg_match('/^0\s/', end($current_month_dates)) ) { // if the last day of the array is Sunday
                break;
            } else {   // if the last day in the array is not Sunday
                array_push($current_month_dates, date('w Y m d', mktime(0,0,0, date("m")+1, 0 + $i, date("Y"))));
                $i += 1;
            }
        }
        $i = 1;
        while ($i < 7) {
            if ( preg_match('/^1\s/',$current_month_dates[0]) ) { // if the first day in the array is Monday
                break;
            } else {   // if the first day in the array is not Monday
                array_unshift($current_month_dates, date('w Y m d', mktime(0,0,0, date("m"), 1 - $i, date("Y"))));
                $i += 1;
            }
        }

        // formatting
        $date_list =[];
        foreach($current_month_dates as $day) {
            [$w, $y, $m, $d] = explode(' ', $day);
            $date_list[] = ['weekday' => $w, 
                            'year' => $y,
                            'month' => $m,
                            'day' => $d,
                            'full' => $day,
                            ];
        }

        // return $date_list;
        return view('stylist.calendar', compact('date_list', 'month'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($month = 0)
    {
        $current_month_dates = $this->getMonth($month);
        [, $last_y, $last_m,] = explode(' ', end($current_month_dates));
        [, $first_y, $first_m,] = explode(' ', $current_month_dates[0]);

                // return end($current_month_dates);
                // return preg_match('#^2\s.*$#', end($current_month_dates));
        // Generating the final week of the previous month & the first week of the next month
        $i = 1;
        $last_index = count($current_month_dates) - 1;
        var_dump('the last day of the month: ' . $current_month_dates[$last_index]);
        while ( $i < 7 ) {
            if ( preg_match('#^0\s.*$#', end($current_month_dates)) ) { // if the last day of the array is Sunday 
                break;
            } else {   // if the last day in the array is not Sunday
                array_push($current_month_dates, date('w Y m d', mktime(0,0,0, $last_m + 1, $i, $last_y)));
                $i += 1;
            }
        }

        $i = 1;
        while ($i < 7) {
            if ( preg_match('#^1\s.*$#', $current_month_dates[0]) ) { // if the first day in the array is Monday
                break;
            } else {   // if the first day in the array is not Monday
                array_unshift($current_month_dates, date('w Y m d', mktime(0,0,0, $first_m, 1 - $i, $first_y)));
                
                $i += 1;
            }
        }

        // formatting
        $date_list =[];
        foreach($current_month_dates as $day) {
            [$w, $y, $m, $d] = explode(' ', $day);
            $date_list[] = ['weekday' => $w, 
                            'year' => $y,
                            'month' => $m,
                            'day' => $d,
                            'full' => $day,
                            ];
        }

        // return $date_list;
        return view('stylist.calendar', compact('date_list', 'month'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
