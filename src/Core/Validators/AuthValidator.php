<?php

namespace Src\Core\Validators;

use Src\Core\RequestValidator;

class AuthValidator extends RequestValidator
{
    public function validateLogin(array $data): bool
    {
        $this->rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $this->messages = [
            'email.required' => 'email is required',
            'email.email' => 'Email must be a valid address',
            'password.required' => 'Password is required'
        ];

        return $this->validate($data);
    }

    public function validateRegister(array $data): bool
    {
        $this->rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $this->messages = [
            'email.required' => 'email is required',
            'email.email' => 'email must be a valid address',
            'password.required' => 'Password is required'
        ];

        return $this->validate($data);
    }
}
