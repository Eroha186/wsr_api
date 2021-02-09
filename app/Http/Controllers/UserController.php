<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\BookingInfoResource;
use App\Http\Resources\UserRecourse;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::query()->create($request->all());
        return response('', 204);
    }

    public function login(LoginRequest $request)
    {
        $user = User::query()->where('phone', $request->phone)->first();
        if($user) {
            if(Hash::check($request->password, $user->password)) {
                return response()->json(
                    [
                        'data' => [
                            'token' => $user->generateToken()
                        ]
                    ]
                );
            }
        }

        return response()->json([
            'errors' => [
                'code' => 401,
                'message' => 'Unauthorized',
                'errors' => ['phone or password incorrect']
            ]
        ], 401);
    }

    public function booking(Request $request)
    {
        $bookings = Booking::whereHas('passengers', function ($query) {
            $query->where('document_number', Auth::user()->document_number);
        })->get();

        $bookings->map(function (Booking $booking) {
            $booking->dispatchFrom->setDate($booking->date_from);

            if($booking->dispatchBack) {
                $booking->dispatchBack->setDate($booking->date_back);
            }

            return $booking;
        });

        return response()->json([
           'data' =>[
               'items' =>  BookingInfoResource::collection($bookings)
           ]
        ]);
    }

    public function info()
    {
        return new UserRecourse(Auth::user());
    }
}
