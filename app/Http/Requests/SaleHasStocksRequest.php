<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SaleHasStocksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'get_product_id' => ['required', 'numeric'],
            'value' => ['required', 'decimal:2', 'gt:0'],
            'quantity' => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function getValidatorInstance()
    {
        $this->sanitize();
        return parent::getValidatorInstance();
    }

    protected function sanitize()
    {
        $cleanValue = preg_replace('/[\.]/', '', $this->request->get('value'));
        $cleanValue = preg_replace('/[,]/', '.', $cleanValue);
        $this->merge([
            'value' => $cleanValue,
        ]);
    }
}
