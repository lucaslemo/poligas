<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $id = $this->route('user') ? $this->route('user') : null;
        $requiredOrNullable = $id ? 'nullable' : 'required';
        $superAdminRule = $id == 1 ? Rule::in(['Administrador']) : Rule::in(['Administrador', 'Gerente', 'Entregador']);
        return [
            'first_name' => [$requiredOrNullable, 'string', 'max:255'],
            'last_name' => [$requiredOrNullable, 'string', 'max:255'],
            'username' => [$requiredOrNullable, 'string', 'max:255', Rule::unique(User::class)->ignore($id)],
            'email' => [$requiredOrNullable, 'email', 'max:255', Rule::unique(User::class)->ignore($id)],
            'status' => [$requiredOrNullable, 'boolean'],
            'type' => ['required', 'string', $superAdminRule],
        ];
    }
}
