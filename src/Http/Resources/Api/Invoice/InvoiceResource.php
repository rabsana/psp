<?php

namespace Rabsana\Psp\Http\Resources\Api\Invoice;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'token'                         => $this->token,

            'status'                        => $this->status,
            'status_info'                   => $this->status_info,

            'qty'                           => $this->qty,
            'qty_prettified'                => $this->qty_prettified,

            'amount'                        => $this->amount,
            'amount_prettified'             => $this->amount_prettified,

            'base'                          => $this->base,
        ];
    }
}
