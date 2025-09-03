<?php

declare(strict_types=1);

namespace App\Interface\Request;

use Hyperf\Validation\Request\FormRequest;

class CreateAccountBalanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id' => 'required|uuid',
            'balance' => 'required|float'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID da conta é obrigatório.',
            'id.uuid' => 'O ID da conta deve ser um UUID válido.',

            'balance.required' => 'O saldo é obrigatório.',
            'balance.float' => 'O saldo deve ser um número válido.',
        ];
    }
}
