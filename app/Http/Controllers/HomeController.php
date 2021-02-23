<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Booking;
use App\Stylist;

class HomeController extends Controller
{
    private $timeSlotTemplate = [
        '09:00:00' => null,
        '09:30:00' => null,
        '10:00:00' => null,
        '10:30:00' => null,
        '11:00:00' => null,
        '11:30:00' => null,
        '12:00:00' => null,
        '12:30:00' => null,
        '13:00:00' => null,
        '13:30:00' => null,
        '14:00:00' => null,
        '14:30:00' => null,
        '15:00:00' => null,
        '15:30:00' => null,
        '16:00:00' => null,
        '16:30:00' => null,
      ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      $user = Auth::user();
      if($user->stylist != null ) {
            $stylist_id = Stylist::where('user_id',$user->id)->first()->id;

            // get schedule of the currently logged-in stylist
            $today = date('Y-m-d').' 00:00:00';
            $tomorrow = date('Y-m-d H-i-s', mktime(0,0,0, date('m'), date('d') + 1, date('Y')));

            ///////////// FOR WEEKEND DEBUGGING ////////////
            // $today = date('Y-m-d H-i-s', mktime(0,0,0, date('m'), date('d') -2, date('Y')));
            // $tomorrow = date('Y-m-d H-i-s', mktime(0,0,0, date('m'), date('d') -1, date('Y')));
            ////////////////////////////////////////////////

            $bookings = Booking::where('stylist_id', $stylist_id)
                                ->where('start_at' , '>', $today) // fetch only future schedule
                                ->where('start_at' , '<', $tomorrow) // fetch only today's schedule
                                ->orderBy('start_at' , 'asc')
                                ->with('treatment')
                                ->get();


            // formatting the fetched data
            $formatted_all_schedule = [];
            $message = '';
            $dates = [];
            if( isset($bookings[0]) ) { // if at least one booking exists on the day
                $schedule = [];
                $test = [];
                foreach($bookings as $booking) {
                    $stylist_id = $booking->stylist_id;
                    $booking_id = $booking->id;
                    [$date, $time] = explode(" ", $booking['start_at']);

                    if (array_key_exists($stylist_id, $schedule)) { // if a stylist has any bookings:
                        if (array_key_exists($date, $schedule[$stylist_id])) { // if the stylist has a certain day in his/her bookings:
                            $schedule[$stylist_id][$date][$time] = ['booking_id' => $booking_id,
                                                                    'availability' => $booking->availability,
                                                                    'duration' => isset($booking->treatment) ? $booking->treatment->duration : null,
                                                                    ];
                        } else {
                            $schedule[$stylist_id][$date] = [$time => ['booking_id' => $booking_id,
                                                                        'availability' => $booking->availability,
                                                                        'duration' => isset($booking->treatment) ? $booking->treatment->duration : null,
                                                                        ]];
                        }
                    } else {
                        $schedule[$stylist_id] = [$date => [$time => ['booking_id' => $booking_id,
                                                                    'availability' => $booking->availability,
                                                                    'duration' => isset($booking->treatment) ? $booking->treatment->duration : null,
                                                                    ]]];
                    }
                }

                // combine fetched data and the template
                foreach ($schedule as $stylist => $dates) {
                    foreach ($dates as $date => $timeSlots) {
                        $full_day_schedule = array_merge($this->timeSlotTemplate, $timeSlots);
                        $formatted_all_schedule[$stylist][$date] = $full_day_schedule;
                    };
                };


                // Reflecting treatment duration
                $isContinuing = false;
                $prevBooking = [];
                $full_schedule[$stylist][$date] = [];
                foreach ( $formatted_all_schedule[$stylist][$date] as $timeslot => $info ) {

                    if ( isset($info) ) {
                        if ( $info['availability'] === 1 ) { // the timeslot is booked

                            //  calculate the number of timeslots one booking takes
                            [$hour, $minute, $s] = explode(":", $info['duration']);
                            $slot = $minute === '30' ? 1 : 0; // if $minute='30', +1 slot. else 0 slot.
                            $slot += (int)$hour * 2; // if $hour='1', +2 slots
                            $isContinuing = $slot > 1 ? true : false;
                            $prevBooking = $info;
                        }
                    } else { // the timeslot is free
                        if( $isContinuing ) { // the timeslot should be booked as continuation of the previous booking
                            $info = $prevBooking; // copy previous booking details
                            $slot -= 1; // one slot (30 min) consumed
                            $isContinuing = $slot > 1 ? true : false; // check whether the slot should still be continuing
                        }
                    }
                    $full_schedule[$stylist][$date][$timeslot] = $info;
                }

                // just sending the schedule for the currently logged-in stylist
                $full_schedule = $full_schedule[$stylist_id];
                $date = array_keys($full_schedule)[0];

            } else {
                $full_schedule = $this->timeSlotTemplate;
                $message = 'There is no booking';

            }

        }
        $full_schedule = $full_schedule ?? [];
        $date = $date ?? [];
        $message = $message ?? "";
        return view('home', compact('full_schedule', 'date', 'message'));
    }
}
