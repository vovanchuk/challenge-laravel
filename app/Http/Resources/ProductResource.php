<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'category' => new CategoryResource($this->category)
        ];
    }
}
