<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'postal_code' => $this->postal_code,
            'national_code' => $this->national_code,
            'certificate_number' => $this->certificate_number,
            'birthdate' => $this->birthdate,
            'father' => $this->father_name,
            'address' => $this->address,
        ];
    }
}
