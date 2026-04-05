<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponse;

abstract class BaseFormRequest extends BaseFormRequest
{
    use ApiResponse;

    /**
     * Autoriza todas as requisições por padrão.
     * Pode ser sobrescrito nos filhos.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Intercepta falha de validação
     * e retorna resposta padronizada.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->error(
                'Erro de validação',
                $validator->errors(),
                422
            )
        );
    }

}