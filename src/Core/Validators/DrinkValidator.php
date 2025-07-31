<?php

namespace Src\Core\Validators;

class DrinkValidator
{
    private array $errors = [];

    public function validate(array $data): bool
    {
        $this->errors = [];

        if (!isset($data['drink'])) {
            $this->errors['drink'] = 'O campo drink é obrigatório.';
        } elseif (!is_numeric($data['drink'])) {
            $this->errors['drink'] = 'O valor deve ser numérico.';
        } elseif ((int)$data['drink'] != $data['drink']) {
            $this->errors['drink'] = 'O valor deve ser um número inteiro.';
        } elseif ((int)$data['drink'] < 1) {
            $this->errors['drink'] = 'O valor deve ser maior que zero.';
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validatedData(array $data): array
    {
        return ['drink' => (int) $data['drink']];
    }
}
