<?php

namespace App\Service\Component;

use App\Models\Company;
use App\Models\CompanyPrices;
use App\Models\CompanyProperty;
use App\Models\File;
use App\Models\News;
use App\Models\Property;
use App\Service\Helpers\BuilderHelper;
use Carbon\Carbon;

class CompanyList extends Base
{
    /**
     * @param array $arParams
     * @return array
     */
    public function prepareParams(array $arParams): array
    {
        $arParams["root_page_url"] = isset($arParams["root_page_url"]) ? $arParams["root_page_url"] : "";

        return parent::prepareParams($arParams);
    }

    /**
     * @param array $propId
     * @return array
     */
    protected function getPropertyIds(array $propId): array
    {
        //root urlbale property
        $rootProp = Property::query()
            ->whereIn('id', $propId)
            ->where([
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->firstOrFail(['id', 'code']);
        //end

        $actualPropIds = array_diff(
            $propId,
            [$rootProp->id]
        );

        //fix
        $builderFilter = [];
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

        return [
            'companyIds' => $companyIds,
            'builderFilter' => $builderFilter,
        ];
    }

    /**
     *
     */
    public function execute(): void
    {
        $this->arResult = $this->getCacheData($this->arParams, function () {
            $totalBuilder = Company::query()
                ->where('active', 1);

            if (isset($this->arParams["filter"]['property_id'])) {
                $data = $this->getPropertyIds($this->arParams["filter"]['property_id']);
                $totalBuilder->whereIn('id', $data['companyIds']);
                $totalBuilder->where($data['builderFilter']);
            }

            //prices
            if (is_array($this->arParams['price_props']) && count($this->arParams['price_props']) > 0) {
                $needProps = array_unique(
                    array_merge(
                        $this->arParams['additional_price_props'] ?? [],
                        $this->arParams['price_props']
                    )
                );
                $priceProps = Property::query()
                    ->whereIn('id', $needProps)
                    ->get()
                    ->each(function (Property $item) {
                        $item->childs = Property::query()
                            ->where('parent_id', $item->id)
                            ->orderByDesc('id')
                            ->get();
                    });
            }
            //end

            return [
                'items' => (clone $totalBuilder)
                    ->limit($this->arParams["limit"])
                    ->orderByDesc('ranging')
                    ->get()
                    ->each(function (Company $item) use ($priceProps) {
                        $item->pageUrl = route(
                            'company-detail',
                            ['companyCode' => $item->code],
                            false
                        );

                        //picture
                        if ($fileInfo = File::query()->find($item->preview_picture)) {
                            $item->preview_picture = File::withRemoteDomain($fileInfo->path);
                        } else {
                            $item->preview_picture = null;
                        }
                        //end

                        //prices
                        $priceInfo = [];
                        $itemPrices = CompanyPrices::query()
                            ->where('company_id', $item->id)
                            ->get();

                        $priceProps->each(function (Property $pricePropItem) use ($itemPrices, &$priceInfo) {
                            $priceList = [];
                            $pricePropItem->childs->each(function (Property $childPricePropItem) use ($itemPrices, &$priceList) {
                                $itemPrices->each(function (CompanyPrices $itemPrice) use ($childPricePropItem, &$priceList) {
                                    if ($childPricePropItem->id === $itemPrice->property_id) {
                                        $priceList[] = [
                                            'type' => $childPricePropItem->title,
                                            'value' => $itemPrice->value,
                                        ];
                                    }
                                });
                            });
                            if (count($priceList) > 0) {
                                $priceInfo[] = [
                                    'id' => $pricePropItem->id,
                                    'title' => $pricePropItem->title,
                                    'values' => $priceList,
                                ];
                            }
                        });
                        $item->prices = $priceInfo;
                        //end

                        $item->openTime = $item->openCloseTime(
                            strtotime(date('H:i', time()))
                        );

                        $item->rating = $item->getRating();
                    })
                    ->all(),
                'full_count' => $totalBuilder->count()
            ];
        });
    }
}
