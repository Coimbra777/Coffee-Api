<?php

namespace Src\Core\Validators;

use Src\Core\RequestValidator;

class UserValidator extends RequestValidator
{
    protected array $rules = [
        'name' => 'min:3',
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected array $messages = [
        'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
        'email.required' => 'O e-mail é obrigatório.',
        'email.email' => 'O e-mail deve ser válido.',
        'password.required' => 'A senha é obrigatória.',
        'password.min' => 'A senha precisa ter no mínimo 6 caracteres.',
    ];

    public function validate(array $data, bool $isUpdate = false): bool
    {
        if ($isUpdate) {
            unset($this->rules['password']);
            unset($this->messages['password.required']);

            if (isset($this->rules['email'])) {
                $this->rules['email'] = str_replace('required|', '', $this->rules['email']);
                $this->rules['email'] = str_replace('required', '', $this->rules['email']);
                $this->rules['email'] = trim($this->rules['email'], '|');
            }

            unset($this->messages['email.required']);
        }

        return parent::validate($data);
    }
}
