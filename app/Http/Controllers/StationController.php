<?php

namespace App\Http\Controllers;

use App\Http\Resources\StationResource;
use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query') ?? '';

        $stations = Station::query()->where('city', 'LIKE', '%' . $query . '%')->orWhere('name', 'LIKE', '%' . $query . '%')->get();

        return response()->json([
           'data' => [
               'items' => StationResource::collection($stations)
           ]
        ]);
    }
}
