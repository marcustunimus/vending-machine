<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendingMachineResource extends JsonResource
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
            "currency_code" => $this->currency_code,
            "location" => $this->location,
            "balance_amount" => $this->balance_amount,
            "currency" => new CurrencyResource($this->currency),
            "coinStacks" => CoinStackResource::collection($this->whenLoaded("coinStacks")),
            "items" => ItemResource::collection($this->whenLoaded("items")),
            "logs" => LogResource::collection($this->whenLoaded("logs")),

            "formattedBalance" => $this->when(property_exists($this, "formattedBalance"), function () {
                return $this->formattedBalance;
            }),

            "extraInfo" => $this->when(property_exists($this, "extraInfo"), function () {
                return $this->extraInfo;
            }),

            // insertCoin extra data.
            "coinStack" => $this->when(property_exists($this, "coinStack"), function () {
                return new CoinStackResource($this->coinStack);
            }),

            // purchaseItem extra data.
            "item" => $this->when(property_exists($this, "item"), function () {
                return new ItemResource($this->item);
            }),

            // returnBalance extra data.
            "returnedBalance" => $this->when(property_exists($this, "returnedBalance"), function () {
                return $this->returnedBalance;
            }),
            "returnedBalanceInCoins" => $this->when(property_exists($this, "returnedBalanceInCoins"), function () {
                return $this->returnedBalanceInCoins;
            }),
            "formattedBalanceInCoins" => $this->when(property_exists($this, "formattedBalanceInCoins"), function () {
                return $this->formattedBalanceInCoins;
            }),
        ];
    }
}
