<?php

namespace Rabsana\Psp\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rabsana\Core\Support\Facades\Json;

class CurrencyController extends Controller
{

    public function index(Request $request)
    {
        try {

            $currencies = collect($request->merchant->currencies)->map(function ($item, $key) {
                return collect($item)->only('id', 'name', 'abbreviation', 'icon_link');
            })->toArray();

            return Json::response(
                200,
                'لیست ارز های که کسب و کار شما پشتیبانی می کند',
                $currencies
            );

            // 
        } catch (Exception $e) {

            info("Showing-merchant-currencies-error: " . $e);
            return Json::response(
                500,
                'مشکلی در نمایش لیست ارز ها وجود دارد'
            );
        }
    }
}
