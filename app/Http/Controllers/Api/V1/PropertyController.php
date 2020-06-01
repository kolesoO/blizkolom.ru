<?php

namespace App\Http\Controllers\Api\V1;

use App\Service\UrlGenerator\UrlGenerator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Property;

class PropertyController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request, UrlGenerator $urlGenerator): array
    {
        $withUrl = (bool) $request->get('with_url', false);
        $rootProperty = $this->getRootProperty($request);
        $builder = Property::query();

        if ($request->get('filtered', false)) {
            $builder->where('filtered', 1);
        }
        if ($request->get('urlable', false)) {
            $builder->where('urlable', 1);
        }
        if ($request->get('root_url', false)) {
            $builder->where('root_url', 1);
        }
        if ($request->get('popular', false)) {
            $builder->where('popular', 1);
        }
        if ($request->get('title', false)) {
            $builder->where('title', 'like', '%' . $request->get('title') . '%');
        }

        $collection = $builder->get();

        return $collection
            ->each(function(Property $item) use ($urlGenerator, $collection, $withUrl, $rootProperty) {
                if ($withUrl) {
                    $prefix = !$item->root_url && $rootProperty ? $rootProperty->code : null;
                    $item->url = $urlGenerator->generateByCollection($item, $collection, $prefix);
                }

                return $item;
            })
            ->toArray();
    }

    protected function getRootProperty(Request $request): ?Property
    {
        $code = explode('/', preg_replace('/https?:\/\/[a-zA-Z0-9.]+/i', '', $request->server('HTTP_REFERER')));
        $code = $code[1] ?? null;

        /** @var Property|null $property */
        $property = Property::query()
            ->where([
                'urlable' => true,
                'root_url' => true,
                'code' => $code,
            ])
            ->first();

        return $property;
    }
}
