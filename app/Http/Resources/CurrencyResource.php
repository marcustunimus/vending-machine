<?php

namespace App\Http\Resources;

use App\Services\VendingMachine\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
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
            "code" => $this->code,
            "euro_rate" => Helpers::formatRate($this->euro_rate),
            "format" => $this->format,
        ];
    }
}
