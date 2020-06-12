<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Register\RegisterRequest;
use App\Repositories\ClientRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /** @var ClientRepository */
    protected $clientRepository;

    /**
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $client = $this->clientRepository->getByLogin(
            $request->get('login')
        );

        if (!is_null($client)) {
            return new JsonResponse('client already exists', Response::HTTP_CONFLICT);
        }

        $client = $this->clientRepository->createModel(
            $request->all()
        );
        $this->clientRepository->save($client);

        return new JsonResponse('register completed');
    }
}
