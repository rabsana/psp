<?php

namespace Rabsana\Psp\Http\Resources\Api\Invoice;

use Illuminate\Http\Resources\Json\JsonResource;

class LightInvoiceResource extends JsonResource
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
            'base'                          => $this->base,
            'qty'                           => $this->qty,
            'qty_prettified'                => $this->qty_prettified,
            'amount'                        => $this->amount,
            'amount_prettified'             => $this->amount_prettified,
            'token'                         => $this->token,
        ];
    }
}
