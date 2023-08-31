<?php

namespace Rabsana\Psp\Http\Middleware;

use Closure;
use Rabsana\Core\Support\Facades\Json;
use Rabsana\Psp\Models\Merchant;

class CheckMerchantTokenMiddleware
{
    public function handle($request, Closure $next)
    {
        if (
            blank($request->header('merchant-token')) ||
            !$merchant = Merchant::token($request->header('merchant-token'))->first()
        ) {
            return $this->invalidMerchantToken();
        }

        if (!$merchant->is_active) {
            return $this->invalidMerchantStatus();
        }

        $merchant->load('user')
            ->load('currencies');

        $request->merge(['merchant' => $merchant]);

        return $next($request);
    }

    private function invalidMerchantToken()
    {
        return Json::response(
            401,
            'Invalid Merchant Token'
        );
    }

    private function invalidMerchantStatus()
    {
        return Json::response(
            403,
            'The merchant status is inactive'
        );
    }
}
