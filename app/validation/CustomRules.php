<?php

namespace App\Validation;

class CustomRules
{

    public function future_date(string $date): bool
    {
        return strtotime($date) > time();
    }
    public function before_date(string $mainDate, string $comparisonField, array $data): bool
    {
        if (empty($mainDate) || empty($data[$comparisonField])) {
            return true;
        }

        return strtotime($mainDate) < strtotime($data[$comparisonField]);
    }
}