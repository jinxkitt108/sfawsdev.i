<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TimeSlot;
use Carbon\Carbon;

class LiveSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    public function getTimeSlots()
    {
        $time_slots = auth('api')->user()->time_slots;
        // foreach ($time_slots as $slot) {
        //     $from = explode(':', $slot->from);
        //     $new_from = Carbon::createFromTime($from[0], $from[1], $from[2])->format('h:i A');
        //     $slot['from'] = $new_from;

        //     $to = explode(':', $slot->to);
        //     $new_to = Carbon::createFromTime($to[0], $to[1], $to[2])->format('h:i A');
        //     $slot['to'] = $new_to;
        // }

        return $time_slots;
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

    public function storeTimeSlot(Request $request)
    {
        $this->validate($request, [
            'day' => 'required',
            'from' => 'required|date_format:H:i',
            'to' => 'required|date_format:H:i|after:from'
        ]);



        $time_slot = TimeSlot::create([
            'user_id' => auth('api')->user()->id,
            'day' => $request['day'],
            'from' => $request['from'],
            'to' => $request['to']
        ]);

        // if ($time_slot) {
        //     $from = explode(':', $time_slot["from"]);
        //     $new_from = Carbon::createFromTime($from[0], $from[1], 00)->format('h:i A');
        //     $time_slot['from'] = $new_from;

        //     $to = explode(':', $time_slot["from"]);
        //     $new_to = Carbon::createFromTime($to[0], $to[1], 00)->format('h:i A');
        //     $time_slot['to'] = $new_to;
        // }

        return $time_slot;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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

    public function destroyTimeSlot($id)
    {
        $time_slot = TimeSlot::findOrFail($id);
        $time_slot->delete();

        return ['status' => 'success', 'message' => 'Time slot deleted!'];
    }
}
