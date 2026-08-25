<?php

class Validator
{
    private function __construct()
    {
    }

    public static function required(mixed $value): bool
    {
        return !empty($value);
    }

    public static function email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function numeric(mixed $value): bool
    {
        return is_numeric($value);
    }

    public static function unique(mixed $value, array $values): bool
    {
        return !in_array($value, $values, true);
    }

    public static function isPositif(mixed $value): bool
    {
        return is_numeric($value) && $value > 0;
    }
}