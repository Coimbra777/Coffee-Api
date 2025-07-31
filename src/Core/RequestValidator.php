<?php

namespace Src\Core;

class RequestValidator
{
    protected array $rules = [];
    protected array $messages = [];
    protected array $errors = [];

    public function validate(array $data): bool
    {
        $this->errors = [];

        foreach ($data as $field => $value) {
            if (!array_key_exists($field, $this->rules) && $field !== '_method') {
                $this->errors[$field][] = "$field não é um campo válido.";
            }
        }

        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rules as $rule) {
                if ($rule === 'required' && (is_null($value) || $value === '')) {
                    $this->errors[$field][] = $this->messages["$field.required"] ?? "O campo $field é obrigatório.";
                }

                if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = $this->messages["$field.email"] ?? "O campo $field deve ser um e-mail válido.";
                }

                if (str_starts_with($rule, 'min:')) {
                    $minLength = (int) explode(':', $rule)[1];
                    if (!empty($value) && strlen($value) < $minLength) {
                        $this->errors[$field][] = $this->messages["$field.min"] ?? "O campo $field deve ter no mínimo $minLength caracteres.";
                    }
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
        return array_intersect_key($data, $this->rules);
    }
}
