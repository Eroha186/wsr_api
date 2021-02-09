<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingInfoResource extends JsonResource
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
            'code' => $this->code,
            'cost' => $this->countCost(),
            'dispatches' => DispatchResource::make($this->dispatchBack ? collect([$this->dispatchFrom, $this->dispatchBack]) : $this->dispatchFrom),
            'passengers' => PassengersResource::collection($this->passengers),
        ];
    }
}
