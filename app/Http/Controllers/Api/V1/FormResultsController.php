<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Jobs\CallBackEmail;
use App\Resources\CallBackResource;
use App\Service\Managers\StatisticManager;
use App\Service\Statistic\Type;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Form;
use App\Models\FormResult;

class FormResultsController extends Controller
{
    use DispatchesJobs;

    /**
     * @param Request $request
     * @param string $code
     * @param StatisticManager $statisticManager
     * @return array
     */
    public function store(Request $request, string $code, StatisticManager $statisticManager): array
    {
        $form = Form::query()
            ->where('code', $code)
            ->firstOrFail();

        $data = $request->all();

        if (FormResult::query()
            ->insert([
                'content' => json_encode($data),
                'form_id' => $form->id,
            ])) {
            $resource = CallBackResource::make($data);
            $this->dispatch(
                new CallBackEmail($resource)
            );
            $statisticManager->create($resource->company_id, Type::CALL_BACK);
        }

        return [];
    }
}
