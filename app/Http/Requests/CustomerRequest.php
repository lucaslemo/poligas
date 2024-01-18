<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
        $id = $this->route('customer') ? $this->route('customer') : null;
        $requiredOrNullable = $id ? 'nullable' : 'required';
        $codeMax = $this->type == 'Pessoa Física' ? 'digits:11' : 'digits:14';
        return [
            'name' => ['required', 'string'],
            'code' => ['nullable', $codeMax, Rule::unique(Customer::class)->ignore($id)],
            'phone_number' => ['nullable', 'digits_between:10,11'],
            'type' => ['required', 'string', Rule::in(['Pessoa Física', 'Pessoa Jurídica'])],

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
            'code' => preg_replace('/[\s\.\/-]/', '', $this->request->get('code')),
            'phone_number' => preg_replace('/[\s\(\)-]/', '', $this->request->get('phone_number')),
            'zip_code' => preg_replace('/[\s\.-]/', '', $this->request->get('zip_code')),
        ]);
    }
}
