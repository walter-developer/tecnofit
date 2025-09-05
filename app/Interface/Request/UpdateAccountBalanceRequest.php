<?php

declare(strict_types=1);

namespace App\Interface\Request;

use Hyperf\Context\Context;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Request\FormRequest;

class UpdateAccountBalanceRequest extends FormRequest
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
            'balance' => 'required|numeric|gt:0'
        ];
    }

    public function validationData(): array
    {
        $data = parent::validationData();
        $id = $this->route('accountId', $data['id'] ?? null);
        return array_merge($data, ['id' => $id]);
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID da conta é obrigatório.',
            'id.uuid' => 'O ID da conta deve ser um UUID válido.',
            'balance.required' => 'O saldo é obrigatório.',
            'balance.numeric' => 'O saldo deve ser um número válido.',
            'balance.gt' => 'O saldo deve ser um valor maior que zero.',
        ];
    }
}
