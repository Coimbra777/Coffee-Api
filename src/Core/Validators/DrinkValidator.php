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

        if (isset($data['date'])) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date'])) {
                $this->errors['date'] = 'Formato de data inválido. Use YYYY-MM-DD.';
            } else {
                $parts = explode('-', $data['date']);
                if (!checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
                    $this->errors['date'] = 'Data inválida.';
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function validatedData(array $data): array
    {
        return [
            'drink' => (int) $data['drink'],
            'date' => $data['date'] ?? null,
        ];
    }
}
