<?php

declare(strict_types=1);

namespace App\Http\Requests\Register;

use App\Http\Requests\Request;

class RegisterRequest extends Request
{
    public function rules(): array
    {
        return [
            'login' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}
