<?php

declare(strict_types=1);

namespace App\Http\Requests\Client;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'login' => 'required',
            'password' => 'required'
        ];
    }
}
