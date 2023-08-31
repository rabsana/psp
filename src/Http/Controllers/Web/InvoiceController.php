<?php

namespace Rabsana\Psp\Http\Controllers\Web;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rabsana\Core\Support\Facades\Math;
use Rabsana\Psp\Http\Requests\Web\Invoice\PayInvoiceRequest;
use Rabsana\Psp\Http\Resources\Api\Invoice\LightInvoiceResource;
use Rabsana\Psp\Models\Invoice;

class InvoiceController extends Controller
{

    public function auth($token, Request $request)
    {
        $invoice = Invoice::token($token)
            ->first();

        if (blank($invoice)) {
            return view("rabsana-psp::web.invoice.not-found");
        }

        if ($invoice->is_expired) {
            return view("rabsana-psp::web.invoice.expired", [
                'callbackUrl'           => $invoice->callback_url
            ]);
        }

        if (!$invoice->is_payable) {
            return view("rabsana-psp::web.invoice.not-payable", [
                'callbackUrl'           => $invoice->callback_url
            ]);
        }

        return view("rabsana-psp::web.invoice.auth", [
            'invoice' => (object) (new LightInvoiceResource($invoice))->toArray(request())
        ]);
    }

    public function pay($token, PayInvoiceRequest $request)
    {
        try {

            $invoice = Invoice::token($token)
                ->first();

            if (blank($invoice)) {
                return view("rabsana-psp::web.invoice.not-found");
            }

            if ($invoice->is_expired) {
                return view("rabsana-psp::web.invoice.expired", [
                    'callbackUrl'           => $invoice->callback_url
                ]);
            }

            if (!$invoice->is_payable) {
                return view("rabsana-psp::web.invoice.not-payable", [
                    'callbackUrl'           => $invoice->callback_url
                ]);
            }

            $getUserTask = config("rabsana-psp.getUserInfoWithUsernameAndPasswordTask");

            if (blank($getUserTask)) {
                throw new Exception("the getUserInfoWithUsernameAndPasswordTask has not defined");
            }

            $user = (new $getUserTask())->run(
                $request->safe()->username,
                $request->safe()->password
            );

            if (blank($user)) {
                return back()->with('error', 'کاربری با مشخصات داده شده یافت نشد')->withInput();
            }

            Invoice::whereId($invoice->id)->update([
                'user_id' => $user->id
            ]);

            $invoice->refresh();


            $getBalanceTask = config("rabsana-psp.getUserBalanceWithCurrencyIdTask");

            if (blank($getBalanceTask)) {
                throw new Exception("the getUserBalanceWithCurrencyIdTask has not defined");
            }

            $userBalance = (new $getBalanceTask())->run($user, $invoice->currency_id);

            if (Math::lessThan((float) $userBalance, (float) $invoice->qty)) {
                return back()->with('error', 'موجودی رمز ارز شما برای پرداخت صورت حساب کافی نمی  باشد')->withInput();
            }

            $payInvoiceTask = config("rabsana-psp.payInvoiceTask");

            if (blank($payInvoiceTask)) {
                throw new Exception("The payInvoiceTask has not defined");
            }

            $invoice
                ->load('user')
                ->load('merchant.user')
                ->load('currency');

            $payInvoiceTaskResult = (new $payInvoiceTask())->run($invoice);


            if (!(bool) $payInvoiceTaskResult['status'] ?? false) {
                throw new Exception($payInvoiceTaskResult['message'] ?? 'something went wrong in payInvoiceTask');
            }



            Invoice::whereId($invoice->id)->update([
                'status' => Invoice::PAID
            ]);

            return view("rabsana-psp::web.invoice.success-payment", [
                'callbackUrl' => $invoice->callback_url
            ]);



            // 
        } catch (Exception $e) {

            Invoice::whereId($invoice->id)->update([
                'status' => Invoice::FAILED
            ]);

            info("Paying-invoice-with-psp-error: " . $e);

            return view("rabsana-psp::web.invoice.failed", [
                'callbackUrl' => $invoice->callback_url
            ]);
        }
    }
}
