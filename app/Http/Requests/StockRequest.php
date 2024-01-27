<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StockRequest extends FormRequest
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
        debugbar()->info($this);
        return [
            'get_product_id' => ['required', 'numeric'],
            'get_brand_id' => ['required', 'numeric'],
            'get_vendor_id' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric'],
            'vendor_value' => ['required', 'decimal:2'],
        ];
    }

    public function getValidatorInstance()
    {
        $this->sanitize();
        return parent::getValidatorInstance();
    }

    protected function sanitize()
    {
        $cleanValue = preg_replace('/[\.]/', '', $this->request->get('vendor_value'));
        $cleanValue = preg_replace('/[,]/', '.', $cleanValue);
        $this->merge([
            'vendor_value' => $cleanValue,
        ]);
    }
}
