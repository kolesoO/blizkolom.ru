<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OwnAccess
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->server('SERVER_ADDR') != $request->server('REMOTE_ADDR')) {
            return new JsonResponse(null, Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
