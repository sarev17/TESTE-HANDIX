<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ContactRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $id = $this->route('id');

        if ($this->isMethod('post')) {
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:contacts,email',
                'phone' => 'nullable|string|max:20',
                'notes' => 'nullable|string',
            ];
        }

        // update (PUT/PATCH)
        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('contacts', 'email')->ignore($id),
            ],
            'phone' => 'sometimes|string|max:20',
            'notes' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            // required
            'name.required' => 'O nome é obrigatório',
            'email.required' => 'O e-mail é obrigatório',

            // format
            'email.email' => 'E-mail inválido',

            // unique
            'email.unique' => 'Este e-mail já está em uso',

            // max
            'name.max' => 'O nome deve ter no máximo 255 caracteres',
            'phone.max' => 'O telefone deve ter no máximo 20 caracteres',
        ];
    }
}