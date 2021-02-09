<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Requests\SeatRequest;
use App\Http\Resources\BookingInfoResource;
use App\Http\Resources\PassengersResource;
use App\Models\Booking;
use App\Models\Passenger;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function booking(BookingRequest $request)
    {
        $dispatch_data = [
            'dispatch_from' => $request->dispatch_from['id'],
            'date_from' => $request->dispatch_from['date'],
            'code' => Str::upper(Str::random(5))
        ];

        if($request->has('dispatch_back')) {
            $dispatch_data['dispatch_back'] = $request->dispatch_back['id'];
            $dispatch_data['date_back'] = $request->dispatch_back['date'];
        }

        $booking = Booking::create($dispatch_data);

        $booking->passengers()->createMany($request->passengers);

        return response()->json([
            'data' => [
                'code' => $booking->code,
            ]
        ]);
    }

    public function info(Booking $booking)
    {
        $booking->dispatchFrom->setDate($booking->date_from);
        if($booking->dispatchBack) {
            $booking->dispatchBack->setDate($booking->date_back);
        }

        return new BookingInfoResource($booking);
    }

    public function getOccupiedPlaces($dispatch_id, $date)
    {
        $occupied = [];

        $passengersFrom = Passenger::query()->whereHas('booking', function ($query) use ($dispatch_id, $date) {
            $query->where([
                'dispatch_from' => $dispatch_id,
                'date_from' => $date
            ]);
        })->get();

        $passengersFrom->map(function ($passenger) use (&$occupied) {
           if ($passenger->palce_from) {
               $occupied[] = [
                   'passenger_id' => $passenger->id,
                   'place' => $passenger->palce_from
               ];
           }
        });

        $passengersBack = Passenger::query()->whereHas('booking', function ($query) use ($dispatch_id, $date) {
            $query->where([
                'dispatch_from' => $dispatch_id,
                'date_from' => $date
            ]);
        })->get();

        $passengersBack->map(function ($passenger) use (&$occupied) {
           if ($passenger->place_back) {
               $occupied[] = [
                   'passenger_id' => $passenger->id,
                   'place' => $passenger->place_back
               ];
           }
        });

        return $occupied;
    }

    public function getOccupiedSeats(Booking $booking)
    {
        return response()->json([
            'data' => [
                'occupied_from' => $this->getOccupiedPlaces($booking->dispatch_from, $booking->date_from),
                'occupied_back' => $booking->dispatch_back ? $this->getOccupiedPlaces($booking->dispatch_back->id, $booking->date_back) : [],
            ]
        ]);
    }

    public function seat(Booking $booking, SeatRequest $request)
    {
        $passenger = Passenger::query()->find($request->passenger);

        if($passenger->booking_id != $booking->id) {
            return response()->json([
               'error' => [
                   'code' => 403,
                   'message' => 'Passenger does not apply to booking'
               ]
            ], 403);
        }

        if ($request->type == 'from') {
            $occupiedPlaces = $this->getOccupiedPlaces($booking->dispatch_from, $booking->date_from);
        } else {
            $occupiedPlaces = $this->getOccupiedPlaces($booking->dispatch_back, $booking->date_back);
        }

        if (in_array($request->seat, Arr::pluck($occupiedPlaces, 'place'))) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Seat is occupied'
                ]
            ], 422);
        }

        if ($request->type == 'from') {
            $passenger->place_from = $request->seat;
        } else {
            $passenger->place_back = $request->seat;
        }

        $passenger->save();

        return response()->json([
            'data' => new PassengersResource($passenger)
        ]);
    }
}
