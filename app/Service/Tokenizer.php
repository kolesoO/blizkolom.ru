<?php

declare(strict_types=1);

namespace App\Service;

use Illuminate\Support\Str;

class Tokenizer
{
    /** @var string */
    protected $token;

    /** @var string */
    protected $hash;

    /**
     * @param int $length
     */
    public function __construct(int $length = 60)
    {
        $this->token = Str::random($length);
        $this->hash = hash('sha256', $this->token);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
}
