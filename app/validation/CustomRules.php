<?php

namespace App\Validation;

use App\Models\UserModel;
use App\Models\SubtaskModel;

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

    public function exist_user_email(string $email){
        $userModel = new UserModel();
        if($userModel->obtenerUsuarioEmail($email)){
            return true;
        }
        return false;
    }
}