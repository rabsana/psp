<?php

namespace Rabsana\Psp\Http\Requests\Api\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'currency_id'   => [
                'required',
                'numeric',
                function ($attribute, $val, $fail) {
                    if (!collect(request()->merchant->currencies)->where('id', $val)->count()) {
                        $fail("ارز انتخاب شده پشتیبانی نمی شود");
                    }
                }
            ],

            'amount'        => [
                'required',
                'numeric',
                'min:0.000000001'
            ],

            'callback_url'  => [
                'required',
                'url'
            ],

            'inquiry_url'   => [
                'nullable',
                'url'
            ]

        ];
    }
}
