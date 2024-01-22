<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class AddressRequest extends FormRequest
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
        $id = $this->route('address') ? $this->route('address') : null;
        if (!$id && ($this->exists('get_customer_id') && $this->exists('get_vendor_id'))) {
            throw ValidationException::withMessages([
                'get_customer_id' => 'O endereço não deve conter cliente e fornecedor',
                'get_vendor_id' => 'O endereço não deve conter cliente e fornecedor',
            ]);
        }
        if (!$id && (!$this->exists('get_customer_id') && !$this->exists('get_vendor_id'))) {
            throw ValidationException::withMessages([
                'get_customer_id' => 'O endereço deve conter cliente ou fornecedor',
                'get_vendor_id' => 'O endereço deve conter cliente ou fornecedor',
            ]);
        }
        $requiredOrNullableCustomer = $id || $this->exists('get_vendor_id') ? 'nullable' : 'required';
        $requiredOrNullableVendor = $id || $this->exists('get_customer_id') ? 'nullable' : 'required';
        return [
            'zip_code' => ['required', 'digits:8'],
            'street' => ['required', 'string'],
            'number' => ['required', 'string'],
            'complement' => ['nullable', 'string'],
            'neighborhood' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'get_customer_id' => [$requiredOrNullableCustomer, 'numeric'],
            'get_vendor_id' => [$requiredOrNullableVendor, 'numeric'],
        ];
    }

    public function getValidatorInstance()
    {
        $this->sanitize();
        return parent::getValidatorInstance();
    }

    protected function sanitize()
    {
        $this->merge([
            'zip_code' => preg_replace('/[\s\.-]/', '', $this->request->get('zip_code')),
        ]);
    }
}
