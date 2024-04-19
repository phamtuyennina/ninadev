<?php

namespace NINA\Database\Eloquent;

use NINA\Core\Support\Facades\Hash;
use NINA\Database\Eloquent\Model;

abstract class Authenticate extends Model
{
    public function setPasswordAttribute(string $password): string
    {
        return Hash::make($password);
    }
}