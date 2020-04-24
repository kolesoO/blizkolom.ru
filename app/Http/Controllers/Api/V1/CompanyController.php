<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\CompanyPrices;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyProperty;
use App\Models\Property;

class CompanyController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request): array
    {
        $builderFilter = [
            ['active', $request->get('active', 1)],
        ];
        $builder = Company::query()
            ->offset($request->get('offset', 0));

        if ($request->get('property_id', false)) {

            //root urlbale property
            $rootProp = Property::query()
                ->whereIn('id', $request->get('property_id'))
                ->where([
                    ['urlable', 1],
                    ['root_url', 1]
                ])
                ->firstOrFail(['id', 'code']);
            //end

            $actualPropIds = array_diff(
                $request->get('property_id', []),
                [$rootProp->id]
            );

            //fix
            if (in_array(2, $actualPropIds) && in_array(3, $actualPropIds)) {
                $builderFilter[] = ['open_from', '<=', Carbon::now()->format('H:i:s')];
                $builderFilter[] = ['open_to', '>=', Carbon::now()->format('H:i:s')];
                unset($actualPropIds[array_search(2, $actualPropIds)]);
                unset($actualPropIds[array_search(3, $actualPropIds)]);
            } elseif (in_array(2, $actualPropIds)) { //Работает сейчас
                $builderFilter[] = ['open_from', '<=', Carbon::now()->format('H:i:s')];
                $builderFilter[] = ['open_to', '>=', Carbon::now()->format('H:i:s')];
                unset($actualPropIds[array_search(2, $actualPropIds)]);
            } elseif (in_array(3, $actualPropIds)) { //Работает 24/7
                $builderFilter[] = ['open_from', '00:00:00'];
                $builderFilter[] = ['open_to', '23:59:59'];
                unset($actualPropIds[array_search(3, $actualPropIds)]);
            }
            //end

            $actualPropIds = array_values($actualPropIds);

            $byRootPropsCollection = CompanyProperty::query()
                ->where('property_id', $rootProp->id)
                ->get(['id', 'company_id']);

            $byInnerPropsCollection = CompanyProperty::query()
                ->whereIn(
                    'property_id',
                    $actualPropIds
                )
                ->get(['id', 'company_id']);

            //company ids
            $companyIds = [];
            if ($byInnerPropsCollection->count() > 0) {
                $companyIds = $byInnerPropsCollection
                    ->pluck('company_id')
                    ->intersect(
                        $byRootPropsCollection
                            ->pluck('company_id')
                            ->toArray()
                    )
                    ->toArray();
            } elseif (count($actualPropIds) == 0) {
                $companyIds = $byRootPropsCollection
                    ->pluck('company_id')
                    ->toArray();
            }
            //end

            $builder->whereIn('id', $companyIds);
        }

        $totalBuilder = $builder;

        $builder->where($builderFilter);

        $resultCollection = $builder
            ->limit($request->get('limit', 10))
            ->orderByDesc('ranging')
            ->get()
            ->each(function(Company $item) {
                $item->map_coords_str = $item->map_coords;
                $item->map_coords = explode(',', $item->map_coords);
                $item->page_url = route(
                    'company-detail',
                    ['companyCode' => $item->code],
                    false
                );
                $item->options = implode(', ', $item->options()->pluck('value')->toArray());

                //picture
                if ($fileInfo = File::query()->find($item->preview_picture)) {
                    $item->preview_picture = File::withRemoteDomain($fileInfo->path);
                } else {
                    $item->preview_picture = null;
                }
                //end

                //prices
                $priceBuilder = CompanyPrices::query()
                    ->where('company_id', $item->id);

//                if (is_array($actualPropIds) && count($actualPropIds) > 0) {
//                    $priceBuilder->whereIn('property_id', $actualPropIds);
//                }

                $item->prices = $priceBuilder->get();
                //end

                $item->openTime = $item->openCloseTime(
                    strtotime(date('H:i', time()))
                );

                $item->rating = $item->getRating();

                return $item;
            });

        return [
            'total' => $totalBuilder->count(),
            'list' => $resultCollection->toArray()
        ];
    }
}
