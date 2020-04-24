<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Review;
use Illuminate\Console\Command;

class UpdateRanging extends Command
{
    /**
     * @var string
     */
    protected $signature = 'company:update-ranging';

    /**
     * @var string
     */
    protected $description = 'Update companies ranging';

    public function handle(): void
    {
        Company::query()
            ->each(static function (Company $company) {
                $result = 0;
                $company->reviews->each(function(Review $item) use (&$result) {
                    $result += $item->rating;
                });

                if ($company->open_from && $company->open_to) {
                    $result += 5;
                }

                if ($company->phone) {
                    $result += 5;
                }

                if ($company->description) {
                    $result += 5;
                }

                if ($company->preview_picture && $company->detail_picture) {
                    $result += 3;
                }

                if ($company->url) {
                    $result += 3;
                }

                if ($company->email) {
                    $result += 3;
                }

                $company->update([
                    'ranging' => $result
                ]);
            });
    }
}
