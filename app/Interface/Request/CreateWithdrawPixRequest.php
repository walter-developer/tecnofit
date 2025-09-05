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

    public function validationData(): array
    {
        $data = parent::validationData();
        $id = $this->route('accountId', $data['id'] ?? null);
        $schedule = $data['schedule'] ?? null;
        return array_merge($data, [
            'account_id' => $id,
            'schedule' => $schedule
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'account_id' => 'required|uuid',
            'method' => 'required|in:' . strtolower(MethodEnum::PIX->value),
            'amount' => 'required|numeric|gt:0',
            'pix' => 'required|array',
            'pix.type' => 'required|in:' . strtolower(TypeKeyEnum::EMAIL->value),
            'pix.key' => 'required|string',
            'schedule' => 'nullable|date_format:Y-m-d H:i|after:now|before:+7 days',
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'O ID da conta é obrigatório.',
            'account_id.uuid' => 'O ID da conta deve ser um UUID válido.',
            'method.required' => 'O método de saque é obrigatório.',
            'method.in' => 'O método de saque informado é inválido. Deve ser: ' . strtolower(MethodEnum::PIX->value),
            'amount.required' => 'O valor do saque é obrigatório.',
            'amount.float' => 'O valor do saque deve ser um número válido.',
            'schedule.date_format' => 'A data de agendamento deve estar no formato Y-m-d H:i.',
            'schedule.after' => 'A data de agendamento deve ser maior que a data e hora atual.',
            'schedule.before' => 'A data de agendamento deve ser anterior a 7 dias no futuro.',
            'pix.required' => 'Os dados do PIX são obrigatórios.',
            'pix.array' => 'Os dados do PIX devem ser enviados como um array.',
            'pix.type.required' => 'O tipo de chave PIX é obrigatório.',
            'pix.type.in' => 'O tipo de chave PIX informado é inválido. Deve ser: ' . strtolower(TypeKeyEnum::EMAIL->value),
            'pix.key.required' => 'A chave PIX é obrigatória.',
            'pix.key.string' => 'A chave PIX deve ser uma string.',
        ];
    }
}
