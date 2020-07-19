<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Concerns\HasClient;
use App\Http\Requests\Statistic\CreateRequest;
use App\Resources\StatisticResource;
use App\Service\Managers\StatisticManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StatisticController extends Controller
{
    use HasClient;

    /** @var StatisticManager */
    private $statisticManager;

    public function __construct(StatisticManager $statisticManager)
    {
        $this->statisticManager = $statisticManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function showMy(Request $request): JsonResponse
    {
        return StatisticResource::collection(
            $this->statisticManager->allByClient($this->getClient())
        )
            ->response($request);
    }

    /**
     * @param CreateRequest $request
     */
    public function store(CreateRequest $request): void
    {
        $this->statisticManager->create(
            (int) $request->get('company_id'),
            (string) $request->get('type')
        );
    }
}
