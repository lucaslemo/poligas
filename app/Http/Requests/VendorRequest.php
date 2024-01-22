<?php

namespace App\Http\Requests;

use App\Models\Vendor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VendorRequest extends FormRequest
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
        $id = $this->route('vendor') ? $this->route('vendor') : null;
        $requiredOrNullable = $id ? 'nullable' : 'required';
        return [
            'name' => ['required', 'string'],
            'cnpj' => ['required', 'digits:14', Rule::unique(Vendor::class)->ignore($id)],
            'phone_number' => ['nullable', 'digits_between:10,11'],

            'zip_code' => [$requiredOrNullable, 'digits:8'],
            'street' => [$requiredOrNullable, 'string'],
            'number' => [$requiredOrNullable, 'string'],
            'complement' => ['nullable', 'string'],
            'neighborhood' => [$requiredOrNullable, 'string'],
            'city' => [$requiredOrNullable, 'string'],
            'state' => [$requiredOrNullable, 'string'],
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
            'cnpj' => preg_replace('/[\s\.\/-]/', '', $this->request->get('cnpj')),
            'phone_number' => preg_replace('/[\s\(\)-]/', '', $this->request->get('phone_number')),
            'zip_code' => preg_replace('/[\s\.-]/', '', $this->request->get('zip_code')),
        ]);
    }
}
