<?php

namespace Src\Core\Validators;

use Src\Core\RequestValidator;

class UserValidator extends RequestValidator
{
    protected array $rules = [
        'name'     => 'min:3',
        'email'    => 'required|email',
        'password' => 'required',
    ];

    protected array $messages = [
        'name.min'          => 'Name must be at least 3 characters',
        'email.required'    => 'Email is required',
        'email.email'       => 'Email must be valid',
        'password.required' => 'Password is required',
    ];

    public function validate(array $data, bool $isUpdate = false): bool
    {
        if ($isUpdate) {
            unset($this->rules['password']);
            unset($this->messages['password.required']);
        }

        return parent::validate($data);
    }
}
