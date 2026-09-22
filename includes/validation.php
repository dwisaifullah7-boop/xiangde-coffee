<?php
/**
 * Validasi input.
 */
function validate_required(array $data, array $fields): array
{
    $errors = [];
    foreach ($fields as $field => $label) {
        if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
            $errors[] = $label . ' wajib diisi.';
        }
    }
    return $errors;
}

function validate_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_min_length(string $value, int $min): bool
{
    return strlen(trim($value)) >= $min;
}

function validate_numeric($value): bool
{
    return is_numeric($value);
}
