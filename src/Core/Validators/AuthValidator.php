<?php

namespace Src\Core\Validators;

use Src\Core\RequestValidator;

class AuthValidator extends RequestValidator
{
    public function validateLogin(array $data): bool
    {
        $this->rules = [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];

        $this->messages = [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'O campo senha deve ter no mínimo 6 caracteres.'
        ];

        return $this->validate($data);
    }

    public function validateRegister(array $data): bool
    {
        $this->rules = [
            'name' => 'min:3',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];

        $this->messages = [
            'name.min' => 'O campo nome deve ter no mínimo 3 caracteres.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'O campo senha deve ter no mínimo 6 caracteres.'
        ];

        return $this->validate($data);
    }
}
