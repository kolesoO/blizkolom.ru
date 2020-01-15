<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CompanyPrices;
use App\Models\Property;
use App\Models\PriceProperties;

class PriceController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request): array
    {
        $builder = CompanyPrices::query();

        if ($request->get('company_id', false)) {
            $builder->whereIn('company_id', $request->get('company_id'));
        }
        //$prices = $builder->get();

        if ($request->get('property_code', false)) {
            $property = Property::query()
                ->where('code', $request->get('property_code'))
                ->firstOrFail(['id']);

            $childProperties = Property::query()
                ->where('parent_id', $property->id)
                ->get(['id']);

            $actualPrice = PriceProperties::query()
                ->whereIn('property_id', $childProperties->pluck('id')->toArray())
                ->get(['id', 'price_id'])
                ->pluck('price_id');

            $builder->whereIn('id', $actualPrice->toArray());
            /*$pricesByProperty = CompanyPrices::query()
                ->whereIn('id', $actualPrice->toArray())
                ->get(['id']);

            $prices = $prices->intersect($pricesByProperty->pluck('id')->toArray());*/
        }

        return $builder
            ->get()
            ->toArray();
    }
}
