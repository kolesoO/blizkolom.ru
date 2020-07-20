<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Register\RegisterRequest;
use App\Repositories\ClientRepository;
use App\Resources\ClientResource;
use App\Service\Tokenizer;
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
     * @param Tokenizer $tokenizer
     * @return JsonResponse
     */
    public function register(RegisterRequest $request, Tokenizer $tokenizer): JsonResponse
    {
        $client = $this->clientRepository->getByLogin(
            $request->get('login')
        );

        if (!is_null($client)) {
            return new JsonResponse('Клиент с таким логином уже зарегистрирован', Response::HTTP_CONFLICT);
        }

        $client = $this->clientRepository->createModel(
            $request->all()
        );
        $this->clientRepository->save($client);
        $this->clientRepository->updateToken($client, $tokenizer->getHash());

        return ClientResource::make($client)
            ->additional(['api_token' => $tokenizer->getToken()])
            ->response($request);
    }
}
