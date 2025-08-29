<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
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

        if (!isset($this->model)) {
            return [
                "error" => "Log model is null."
            ];
        }

        return [
            "id" => $this->id,
            "vending_machine_id" => $this->vending_machine_id,
            "type" => $this->type,
            "text" => $this->text,
        ];
    }
}
