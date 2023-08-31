<?php

namespace Rabsana\Psp\Http\Controllers\Api;

use Exception;
use Rabsana\Psp\Models\Invoice;
use Rabsana\Psp\Http\Requests\Api\Invoice\StoreInvoiceRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rabsana\Core\Support\Facades\Json;
use Rabsana\Core\Support\Facades\Math;
use Rabsana\Psp\Http\Resources\Api\Invoice\InvoiceResource;

class InvoiceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Rabsana\Psp\Http\Requests\Api\Invoice\StoreInvoiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request)
    {
        DB::beginTransaction();
        try {

            $currency = collect($request->merchant->currencies)->where('id', $request->safe()->currency_id)->first();
            $qty = null;
            if (isset($currency->buy) && $currency->buy != 0) {
                $qty = Math::divide((float) $request->safe()->amount, (float) $currency->buy);
            }

            $invoiceId = Invoice::create([
                'merchant_id'           => $request->merchant->id,
                'currency_id'           => $request->safe()->currency_id,
                'token'                 => $this->generateUniqueToken(),
                'amount'                => $request->safe()->amount,
                'qty'                   => $qty,
                'base'                  => $currency->abbreviation ?? $currency->name ?? '',
                'quote'                 => 'TOMAN',
                'callback_url'          => $request->safe()->callback_url,
                'inquiry_url'           => $request->safe()->inquiry_url ?? null,
                'created_at'            => now()

            ])->id;

            $invoice = Invoice::findOrFail($invoiceId);

            DB::commit();


            return Json::response(
                200,
                'صورت حساب با موفقیت ثبت شد',
                [
                    'payUrl'            => route("rabsana-psp.web.invoices.auth", ['token' => $invoice->token]),
                    'invoiceToken'      => $invoice->token
                ]
            );

            // 
        } catch (Exception $e) {

            DB::rollBack();
            info("Storing-invoice-error-in-psp: " . $e);

            return Json::response(
                500,
                'متاسفانه مشکلی در ایجاد صورت حساب وجود دارد'
            );
        }
    }

    public function show($token)
    {
        $invoice = Invoice::token($token)
            ->with('user')
            ->with('currency')
            ->first();

        if (blank($invoice)) {
            return Json::response(
                404,
                'صورت حساب یافت نشد'
            );
        }

        return Json::response(
            200,
            'جزییات صورت حساب',
            (new InvoiceResource($invoice))->toArray(request())
        );
    }

    private function generateUniqueToken()
    {
        return Str::random() . time() . Str::random();
    }
}
