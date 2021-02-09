<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DispatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'dispatch_id' => $this->id,
            'dispatch_code' => $this->dispatch_code,
            'from' => [
                'city' => $this->stationFrom->city,
                'station' => $this->stationFrom->name,
                'station_id' => $this->stationFrom->id,
                'date' => $this->date,
                'time' => $this->time_from,
            ],
            'to' => [
                'city' => $this->stationTo->city,
                'station' => $this->stationTo->name,
                'station_id' => $this->stationTo->id,
                'date' => $this->date,
                'time' => $this->time_to,
            ],
            'cost' => $this->cost
        ];
    }
}
