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

        // 1. Verifica se há campos inválidos (não definidos nas regras)
        foreach ($data as $field => $value) {
            if (!array_key_exists($field, $this->rules)) {
                $this->errors[$field][] = "$field is not a valid field";
            }
        }

        // 2. Valida os campos definidos nas regras
        foreach ($this->rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($rules as $rule) {
                if ($rule === 'required' && (is_null($value) || $value === '')) {
                    $this->errors[$field][] = $this->messages["$field.required"] ?? "$field is required";
                }
                if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = $this->messages["$field.email"] ?? "$field must be a valid email";
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
