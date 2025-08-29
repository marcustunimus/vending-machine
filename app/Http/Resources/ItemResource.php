<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (property_exists($this, "error")) {
            return [
                "error" => $this->error,
            ];
        }

        if (property_exists($this, "success")) {
            return [
                "success" => $this->success,
            ];
        }

        return [
            "id" => $this->id,
            "vending_machine_id" => $this->vending_machine_id,
            "name" => $this->name,
            "price" => $this->price,
            "remaining_quantity" => $this->remaining_quantity,
        ];
    }
}
