<?php

declare(strict_types=1);

namespace App\Interface\Request;

use App\Domain\Enums\MethodEnum;
use App\Domain\Enums\TypeKeyEnum;
use Hyperf\Validation\Request\FormRequest;

class CreateWithdrawPixRequest extends FormRequest
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
            'method' => 'required|in:' . MethodEnum::PIX->value,
            'amount' => 'required|float',
            'schedule' => 'nullable|date_format:Y-m-d H:i:s',
            'pix' => 'required|array',
            'pix.type' => 'required|in:' . TypeKeyEnum::EMAIL->value,
            'pix.key' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'method.required' => 'O método de saque é obrigatório.',
            'method.in' => 'O método de saque informado é inválido. Deve ser: ' . MethodEnum::PIX->value,

            'amount.required' => 'O valor do saque é obrigatório.',
            'amount.float' => 'O valor do saque deve ser um número válido.',

            'schedule.date_format' => 'A data agendada deve estar no formato Y-m-d H:i:s.',

            'pix.required' => 'Os dados do PIX são obrigatórios.',
            'pix.array' => 'Os dados do PIX devem ser enviados como um array.',

            'pix.type.required' => 'O tipo de chave PIX é obrigatório.',
            'pix.type.in' => 'O tipo de chave PIX informado é inválido. Deve ser: ' . TypeKeyEnum::EMAIL->value,

            'pix.key.required' => 'A chave PIX é obrigatória.',
            'pix.key.string' => 'A chave PIX deve ser uma string.',
        ];
    }
}
