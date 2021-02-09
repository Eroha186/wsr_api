<?php

namespace App\Http\Controllers;

use App\Http\Requests\DispatchRequest;
use App\Http\Resources\DispatchResource;
use App\Models\Dispatch;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    public function getDispatchByDate($from, $to, $date)
    {
        $dispatches = Dispatch::query()->whereHas('stationFrom', function ($query) use ($from) {
            $query->where('id', $from);
        })->whereHas('stationTo', function ($query) use ($to) {
            $query->where('id', $to);
        })->get();

        $dispatches->map(function ($dispatch) use ($date) {
            $dispatch->setDate($date);
            return $dispatch;
        });

        return $dispatches;
    }

    public function search(DispatchRequest $request)
    {
        $dispatchTo = $this->getDispatchByDate($request->from, $request->to, $request->date1);

        $dispatchBack = [];
        if($request->has('date2')) {
            $dispatchBack = $this->getDispatchByDate($request->to, $request->from, $request->date2);
        }

        return response()->json([
            'data' => [
                'dispatch_to' => DispatchResource::collection($dispatchTo),
                'dispatch_back' => DispatchResource::collection($dispatchBack)
            ]
        ]);
    }
}
