<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\LoginRequest;
use App\Repositories\ClientRepository;
use App\Resources\ClientResource;
use App\Service\Tokenizer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
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
     * @param LoginRequest $request
     * @param Tokenizer $tokenizer
     * @return JsonResponse
     */
    public function login(LoginRequest $request, Tokenizer $tokenizer): JsonResponse
    {
        $client = $this->clientRepository->getByLogin(
            $request->get('login')
        );

        if (is_null($client)) {
            throw (new ModelNotFoundException())
                ->setModel(ClientRepository::getModelClass());
        }

        if (!Hash::check($request->get('password'), $client->password)) {
            return new JsonResponse('invalid password', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->clientRepository->updateToken($client, $tokenizer->getHash());

        return ClientResource::make($client)
            ->additional(['api_token' => $tokenizer->getToken()])
            ->response($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        $client = $this->clientRepository->find(
            Auth::guard('api')->user()->id
        );

        if (is_null($client)) {
            throw (new ModelNotFoundException())
                ->setModel(ClientRepository::getModelClass());
        }

        return ClientResource::make($client)
            ->response($request);
    }
}
