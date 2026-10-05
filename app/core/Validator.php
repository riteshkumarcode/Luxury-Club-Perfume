<?php
declare(strict_types=1);

namespace App\Core;

class Validator
{
    private array $errors = [];
    private array $data = [];

    public function validate(array $data, array $rules, array $customMessages = []): bool
    {
        $this->data = $data;
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            if (is_string($fieldRules)) {
                $fieldRules = explode('|', $fieldRules);
            }

            foreach ($fieldRules as $ruleString) {
                $ruleParts = explode(':', $ruleString, 2);
                $ruleName = $ruleParts[0];
                $ruleParam = $ruleParts[1] ?? null;

                // Check required first
                if ($ruleName === 'required') {
                    if ($value === null || (is_string($value) && trim($value) === '') || (is_array($value) && empty($value))) {
                        $this->addError($field, $customMessages["$field.required"] ?? $this->formatFieldName($field) . " is required.");
                        break; // Skip subsequent rules for empty required field
                    }
                    continue;
                }

                // If not required and empty, skip remaining validations
                if ($value === null || (is_string($value) && trim($value) === '')) {
                    continue;
                }

                switch ($ruleName) {
                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, $customMessages["$field.email"] ?? "Please enter a valid email address.");
                        }
                        break;

                    case 'min':
                        $min = (int)$ruleParam;
                        if (is_string($value) && mb_strlen($value) < $min) {
                            $this->addError($field, $customMessages["$field.min"] ?? $this->formatFieldName($field) . " must be at least {$min} characters.");
                        } elseif (is_numeric($value) && $value < $min) {
                            $this->addError($field, $customMessages["$field.min"] ?? $this->formatFieldName($field) . " must be at least {$min}.");
                        }
                        break;

                    case 'max':
                        $max = (int)$ruleParam;
                        if (is_string($value) && mb_strlen($value) > $max) {
                            $this->addError($field, $customMessages["$field.max"] ?? $this->formatFieldName($field) . " may not exceed {$max} characters.");
                        } elseif (is_numeric($value) && $value > $max) {
                            $this->addError($field, $customMessages["$field.max"] ?? $this->formatFieldName($field) . " may not exceed {$max}.");
                        }
                        break;

                    case 'numeric':
                        if (!is_numeric($value)) {
                            $this->addError($field, $customMessages["$field.numeric"] ?? $this->formatFieldName($field) . " must be a number.");
                        }
                        break;

                    case 'integer':
                        if (!filter_var($value, FILTER_VALIDATE_INT)) {
                            $this->addError($field, $customMessages["$field.integer"] ?? $this->formatFieldName($field) . " must be an integer.");
                        }
                        break;

                    case 'pincode':
                        // Indian pincode: 6 digits, does not start with 0
                        if (!preg_match('/^[1-9]\d{5}$/', (string)$value)) {
                            $this->addError($field, $customMessages["$field.pincode"] ?? "Please enter a valid 6-digit Indian PIN code.");
                        }
                        break;

                    case 'phone':
                        // Clean digits and check length
                        $clean = preg_replace('/[^\d+]/', '', (string)$value);
                        if (strlen($clean) < 10 || strlen($clean) > 15) {
                            $this->addError($field, $customMessages["$field.phone"] ?? "Please enter a valid phone number.");
                        }
                        break;

                    case 'in':
                        $allowed = explode(',', (string)$ruleParam);
                        if (!in_array((string)$value, $allowed, true)) {
                            $this->addError($field, $customMessages["$field.in"] ?? "Selected {$this->formatFieldName($field)} is invalid.");
                        }
                        break;

                    case 'unique':
                        // format: unique:table,column,except_id
                        $params = explode(',', (string)$ruleParam);
                        $table = $params[0] ?? '';
                        $col = $params[1] ?? $field;
                        $exceptId = $params[2] ?? null;

                        $sql = "SELECT COUNT(*) as cnt FROM `{$table}` WHERE `{$col}` = :val";
                        $binds = ['val' => $value];
                        if ($exceptId !== null && $exceptId !== '') {
                            $sql .= " AND `id` != :except_id";
                            $binds['except_id'] = $exceptId;
                        }
                        $row = Database::fetch($sql, $binds);
                        if ($row && ((int)($row['cnt'] ?? 0)) > 0) {
                            $this->addError($field, $customMessages["$field.unique"] ?? "This " . $this->formatFieldName($field) . " is already in use.");
                        }
                        break;

                    case 'same':
                        $otherField = $ruleParam;
                        $otherVal = $data[$otherField] ?? null;
                        if ($value !== $otherVal) {
                            $this->addError($field, $customMessages["$field.same"] ?? $this->formatFieldName($field) . " does not match " . $this->formatFieldName($otherField) . ".");
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(?string $field = null): ?string
    {
        if ($field !== null) {
            return $this->errors[$field][0] ?? null;
        }
        foreach ($this->errors as $messages) {
            if (!empty($messages)) {
                return $messages[0];
            }
        }
        return null;
    }

    public function hasError(string $field): bool
    {
        return !empty($this->errors[$field]);
    }

    private function formatFieldName(string $field): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $field));
    }
}
