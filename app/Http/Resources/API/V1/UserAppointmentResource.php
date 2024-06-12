<?php

namespace App\Http\Resources\API\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'position' => $this->position,
            'status' => $this->status_fa,
            'date' => $this->reserve->date,
            'time' => [
                'start' => $this->reserve->start_time,
                'end' => $this->reserve->end_time
            ],
            'created_at' => $this->created_at
        ];
    }
}
