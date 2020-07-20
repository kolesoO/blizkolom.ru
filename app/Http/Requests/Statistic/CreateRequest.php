<?php

declare(strict_types=1);

namespace App\Http\Requests\Statistic;

use App\Http\Requests\Request;

class CreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'company_id' => 'required|int|min:1',
            'type' => 'required',
        ];
    }
}
