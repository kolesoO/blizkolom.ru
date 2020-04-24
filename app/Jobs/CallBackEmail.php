<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Company;
use App\Resources\CallBack;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class CallBackEmail implements ShouldQueue
{
    /** @var CallBack */
    protected $data;

    /**
     * @param CallBack $data
     */
    public function __construct(CallBack $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        $company = Company::query()->find($this->data->company_id);

        if (is_null($company)) {
            return;
        }

        Mail::send(
            'mail.callback',
            [
                'company_name' => $company->name,
                'server_name' => config('app.name'),
                'server_url' => config('app.url'),
                'client_name' => $this->data->name,
                'client_phone' => $this->data->phone,
            ],
            function (Message $message) use ($company) {
                $message->from('help@blizkolom.ru')
                    ->to($company->email)
                    ->subject('Перезвоните мне пожалуйста');

            }
        );
    }
}
