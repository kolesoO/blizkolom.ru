<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Client\ChangePwdRequest;
use App\Http\Requests\Client\CreateRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Resources\ClientResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
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

    public function show(string $id)
    {
        return $id;
    }

    /**
     * @param UpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        $client = $this->clientRepository->find((int) $id);

        if (is_null($client)) {
            throw (new ModelNotFoundException())
                ->setModel(ClientRepository::getModelClass());
        }

        $this->clientRepository->save(
            $client->fill($request->all())
        );

        $client = $this->clientRepository->find((int) $id);

        return ClientResource::make($client)->response($request);
    }

    /**
     * @param CreateRequest $request
     * @return JsonResponse
     */
    public function store(CreateRequest $request): JsonResponse
    {
        $client = $this->clientRepository->createModel(
            $request->all()
        );

        $this->clientRepository->save($client);

        return ClientResource::make($client)->response($request);
    }

    /**
     * @param ChangePwdRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function changePwd(ChangePwdRequest $request, string $id): JsonResponse
    {
        $client = $this->clientRepository->find((int) $id);

        if (is_null($client)) {
            throw (new ModelNotFoundException())
                ->setModel(ClientRepository::getModelClass());
        }

        $client->password = Hash::make(
            $request->get('password')
        );

        $this->clientRepository->save($client);

        return ClientResource::make($client)->response($request);
    }
}
