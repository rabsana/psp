<?php

namespace Rabsana\Psp\Http\Requests\Admin\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class StoreMerchantRequest extends FormRequest
{
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
            'user_id'       => [
                'required',
                'numeric'
            ],
            'name'          => [
                'required',
                'max:255'
            ],
            'is_active'     => [
                'required',
                'numeric',
                'boolean'
            ],
            'logo'          => [
                'nullable',
                'image',
            ],
            'currency_ids'  => [
                'required',
                'array',
                'min:1'
            ],
            'currency_ids.*'  => [
                'numeric'
            ]
        ];
    }
}
