<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyPrices;
use App\Models\CompanyProperty;
use App\Models\File;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Pages extends WebPageController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string|null $propertyCode
     * @param string|null $filteredPropertiesCode
     * @return View
     */
    public function index(string $propertyCode = null, string $filteredPropertiesCode = null): View
    {
        $property = Property::query()
            ->where([
                ['code', $propertyCode],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->first();

        if (!$property) {
            return $this->section(null, $propertyCode, null, $filteredPropertiesCode);
        }

        $propsId = [$property->id];
        $priceProps = [5, 6, 20, 26];
        $additPriceProps = [];

        //filtered props
        if ($filteredPropertiesCode) {
            $props = $this->parseFilterCode($filteredPropertiesCode);
            if (count($props) > 0) {
                $rs = Property::query()
                    ->where('filtered', 1)
                    ->whereIn('code', $props)
                    ->pluck('id');
                $propsId += array_merge($propsId, $rs->toArray());
                $additPriceProps = array_reverse($rs->toArray());
            }
        }
        //end

        $propsId = array_unique($propsId);

        $companiesByPropCount = $this->getCompanyBeProperty($propsId)->count(['id']);

        return view($this->getActualPage('index'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => "/{root_code}",
                        "replace" => [
                            "title" => [
                                "{genetiv}" => $property->genetiv,
                                "{gdetiv}" => $property->gdetiv,
                                "{nominativ}" => $property->nominativ,
                                '{num}' => $companiesByPropCount,
                            ],
                            "description" => [
                                "{genetiv}" => $property->genetiv,
                                "{gdetiv}" => $property->gdetiv,
                                "{nominativ}" => $property->nominativ,
                                '{num}' => $companiesByPropCount,
                            ],
                            "keywords" => [
                                "{genetiv}" => $property->genetiv,
                                "{gdetiv}" => $property->gdetiv,
                                "{nominativ}" => $property->nominativ,
                                '{num}' => $companiesByPropCount,
                            ],
                            "h1" => [
                                "{genetiv}" => $property->genetiv,
                                "{gdetiv}" => $property->gdetiv,
                                "{nominativ}" => $property->nominativ,
                                '{num}' => $companiesByPropCount,
                            ],
                        ]
                    ]),
                    'company_count' => [
                        'count' => $companiesByPropCount,
                        'suffix' => GetDeclNum($companiesByPropCount)
                    ],
                ],
                'root_property_title' => $property->title,
                'property_id' => $propsId,
                'company_list' => app()->component->includeComponent("CompanyList", $this->getWithDevicePrefix("default"), [
                    "root_page_url" => '/',
                    "date_format" => "d.m.Y",
                    'limit' => 10,
                    'property_code' => $propertyCode,
                    'property_id' => $propsId,
                    'price_props' => $priceProps,
                    'additional_price_props' => $additPriceProps,
                    'filter' => [
                        'property_id' => $propsId,
                    ]
                ])
            ],
            $this->getHeaderData([
                'replace' => ['{code}' => $propertyCode ? $propertyCode : ''],
            ]),
            $this->getFooterData()
        ));
    }

    /**
     * @param string|null $propertyCode
     * @param string $property2Code
     * @param string|null $property3Code
     * @param string|null $filteredPropertiesCode
     * @return View
     */
    public function section(
        ?string $propertyCode,
        string $property2Code,
        string $property3Code = null,
        string $filteredPropertiesCode = null
    ): View {
        if ($propertyCode == 'priem') {
            return $this->company(null, $property2Code);
        }
        //end

        $rootProperty = Property::query()
            ->where([
                ['code', $propertyCode],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->first();

        if (!$rootProperty && !$property3Code) {
            $property3Code = $property2Code;
            $property2Code = $propertyCode;
            $propertyCode = null;
            $rootProperty = Property::query()
                ->where([
                    ['code', $propertyCode],
                    ['urlable', 1],
                    ['root_url', 1]
                ])
                ->firstOrFail();
        }

        $mainProperty = Property::query()
            ->where([
                ['code', $property2Code],
                ['urlable', 1]
            ])
            ->firstOrFail();

        if (!is_null($property3Code)) {
            $innerProperty = Property::query()
                ->where([
                    ['code', $property3Code],
                    ['urlable', 1],
                    ['parent_id', $mainProperty->id]
                ])
                ->firstOrFail();
        }

        $mainProperty = $mainProperty->toArray();
        $propsId = [$rootProperty->id, $mainProperty['id']];

        if (isset($innerProperty)) {
            $innerProperty = $innerProperty->toArray();
            $propsId[] = $innerProperty['id'];
        }

        $priceProps = [5, 6, 20, 26];
        $additPriceProps = [];

        //filtered props
        if ($filteredPropertiesCode) {
            $props = $this->parseFilterCode($filteredPropertiesCode);
            if (count($props) > 0) {
                $rs = Property::query()
                    ->where('filtered', 1)
                    ->whereIn('code', $props)
                    ->pluck('id');
                $propsId += array_merge($propsId, $rs->toArray());
                $additPriceProps = array_reverse($rs->toArray());
            }
        }
        //end

        $propsId = array_unique($propsId);

        //seo
        $seoCode = '/{root_code}/' . $property2Code;
        if (!is_null($property3Code)) {
            $seoCode .= '/' . $property3Code;
        }
        //end

        $companiesByPropCount = $this->getCompanyBeProperty($propsId)->count(['id']);

        return view($this->getActualPage('section'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => $seoCode,
                        "replace" => [
                            "title" => [
                                "{genetiv}" => $rootProperty->genetiv,
                                "{gdetiv}" => $rootProperty->gdetiv,
                                "{nominativ}" => $rootProperty->nominativ,
                                '{num}' => $companiesByPropCount,
                            ],
                            "description" => [
                                "{genetiv}" => $rootProperty->genetiv,
                                "{gdetiv}" => $rootProperty->gdetiv,
                                "{nominativ}" => $rootProperty->nominativ,
                                '{num}' => $companiesByPropCount,
                            ],
                            "keywords" => [
                                "{genetiv}" => $rootProperty->genetiv,
                                "{gdetiv}" => $rootProperty->gdetiv,
                                "{nominativ}" => $rootProperty->nominativ,
                                '{num}' => $companiesByPropCount,
                            ],
                            "h1" => [
                                "{genetiv}" => $rootProperty->genetiv,
                                "{gdetiv}" => $rootProperty->gdetiv,
                                "{nominativ}" => $rootProperty->nominativ,
                                '{num}' => $companiesByPropCount,
                            ],
                        ]
                    ]),
                    'company_count' => [
                        'count' => $companiesByPropCount,
                        'suffix' => GetDeclNum($companiesByPropCount)
                    ],
                ],
                'property_id' => $propsId,
                'root_property_title' => $rootProperty->title,
                'company_list' => app()->component->includeComponent("CompanyList", $this->getWithDevicePrefix("default"), [
                    "root_page_url" => '/',
                    "date_format" => "d.m.Y",
                    'limit' => 10,
                    'property_code' => $propertyCode,
                    'property_id' => $propsId,
                    'additional_price_props' => $additPriceProps,
                    'price_props' => $priceProps,
                    'filter' => [
                        'property_id' => $propsId,
                    ],
                ])
            ],
            $this->getHeaderData([
                'replace' => ['{code}' => $propertyCode ? $propertyCode : ''],
            ]),
            $this->getFooterData()
        ));
    }

    /**
     * @param string $companyCode
     * @return View
     */
    public function company(string $companyCode): View
    {
        $rootProperty = Property::query()
            ->where([
                ['code', null],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->firstOrFail();

        $company = Company::query()
            ->where('code', $companyCode)
            ->firstOrFail();

        CompanyProperty::query()
            ->where([
                ['property_id', $rootProperty->id],
                ['company_id', $company->id]
            ])
            ->firstOrFail();

        //images
        if ($image = File::query()->find($company->preview_picture)) {
            $image->path = File::withRemoteDomain($image->path);
            $company->preview_picture = $image;
        } else {
            $company->preview_picture = null;
        }
        if ($image = File::query()->find($company->detail_picture)) {
            $image->path = File::withRemoteDomain($image->path);
            $company->detail_picture = $image;
        } else {
            $company->detail_picture = null;
        }
        //end

        //prices
        $priceProps = Property::query()
            ->whereIn('id', [5, 6, 20, 26])
            ->get()
            ->each(function (Property $item) {
                $item->childs = Property::query()
                    ->where('parent_id', $item->id)
                    ->orderByDesc('id')
                    ->get()
                    ->each(function (Property $subItem) {
                        $subItem->childs = Property::query()
                            ->where('parent_id', $subItem->id)
                            ->orderByDesc('id')
                            ->get();
                    });
            });

        $priceInfo = [];
        $itemPrices = CompanyPrices::query()
            ->where('company_id', $company->id)
            ->get();
        $priceProps->each(function (Property $pricePropItem) use ($itemPrices, &$priceInfo) {
            $priceList = [];
            $pricePropItem->childs->each(function (Property $childPricePropItem) use ($itemPrices, &$priceList) {
                $itemPrices->each(function (CompanyPrices $itemPrice) use ($childPricePropItem, &$priceList, $itemPrices) {
                    if ($childPricePropItem->id === $itemPrice->property_id) {
                        $childs = [];
                        $childPricePropItem->childs->each(function ($subChildPricePropItem) use ($itemPrices, &$childs) {
                            $itemPrices->each(function (CompanyPrices $itemPrice) use ($subChildPricePropItem, &$childs) {
                                if ($subChildPricePropItem->id === $itemPrice->property_id) {
                                    $childs[] = [
                                        'type' => $subChildPricePropItem->title,
                                        'value' => $itemPrice->value,
                                    ];
                                }
                            });
                        });
                        $priceList[] = [
                            'type' => $childPricePropItem->title,
                            'value' => $itemPrice->value,
                            'childs' => $childs,
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
        $company->prices = $priceInfo;
        //end

        $company->openTime = $company->openCloseTime(
            strtotime(date('H:i', time()))
        );

        $company->rating = $company->getRating();

        $companiesByPropCount = $this->getCompanyBeProperty([$rootProperty->id])->count(['id']);

        return view($this->getActualPage('company'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => '/priem/{code}',
                        "replace" => [
                            "title" => [
                                '{title}' => str_replace(
                                    ['{genetiv}', '{gdetiv}', '{nominativ}'],
                                    [$rootProperty->genetiv, $rootProperty->gdetiv, $rootProperty->nominativ],
                                    $company->title
                                )
                            ],
                            "description" => [
                                "{description}" => str_replace(
                                    ['{genetiv}', '{gdetiv}', '{nominativ}'],
                                    [$rootProperty->genetiv, $rootProperty->gdetiv, $rootProperty->nominativ],
                                    $company->description
                                )
                            ],
                            "keywords" => [
                                "{keywords}" => str_replace(
                                    ['{genetiv}', '{gdetiv}', '{nominativ}'],
                                    [$rootProperty->genetiv, $rootProperty->gdetiv, $rootProperty->nominativ],
                                    $company->keywords
                                )
                            ],
                            "h1" => [
                                "{h1}" => str_replace(
                                    ['{genetiv}', '{gdetiv}', '{nominativ}'],
                                    [$rootProperty->genetiv, $rootProperty->gdetiv, $rootProperty->nominativ],
                                    $company->h1
                                )
                            ],
                        ],
                    ]),
                    'company_count' => [
                        'count' => $companiesByPropCount,
                        'suffix' => GetDeclNum($companiesByPropCount)
                    ],
                ],
                'company' => $company,
                'root_property_title' => $rootProperty->title
            ],
            $this->getHeaderData([
                'replace' => ['{code}' => $rootProperty ? $rootProperty->code : ''],
            ]),
            $this->getFooterData()
        ));
    }

    /**
     * @param string|null $propertyCode
     * @return View
     */
    public function how(?string $propertyCode = null): View
    {
        $property = Property::query()
            ->where([
                ['code', $propertyCode],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->firstOrFail(['id', 'title']);

        $companiesByPropCount = $this->getCompanyBeProperty([$property->id])->count(['id']);

        return view($this->getActualPage('how-to'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => "/{root_code}/kak-sdat"
                    ]),
                    'company_count' => [
                        'count' => $companiesByPropCount,
                        'suffix' => GetDeclNum($companiesByPropCount)
                    ],
                ],
                'root_property_title' => $property->title,
                'property_id' => [$property->id]
            ],
            $this->getHeaderData([
                'replace' => ['{code}' => $property ? $property->code : ''],
            ]),
            $this->getFooterData()
        ));
    }

    /**
     * @param string|null $propertyCode
     * @return View
     */
    public function netochnost(?string $propertyCode = null): View
    {
        $property = Property::query()
            ->where([
                ['code', $propertyCode],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->firstOrFail(['id', 'title']);

        $companiesByPropCount = $this->getCompanyBeProperty([$property->id])->count(['id']);

        return view($this->getActualPage('netochnost'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => "/{root_code}/netochnost"
                    ]),
                    'company_count' => [
                        'count' => $companiesByPropCount,
                        'suffix' => GetDeclNum($companiesByPropCount)
                    ],
                ],
                'root_property_title' => $property->title,
                'property_id' => [$property->id]
            ],
            $this->getHeaderData([
                'replace' => ['{code}' => $property ? $property->code : ''],
            ]),
            $this->getFooterData()
        ));
    }

    public function prices(): View
    {
        $prices = CompanyPrices::query()
            ->get();
        $priceIds = array_unique(
            $prices->pluck('property_id')
                ->toArray()
        );

        $allProps = Property::query()
            ->get()
            ->toArray();

        $propsTree = [];
        foreach ($allProps as $prop) {
            if (!is_null($prop['parent_id'])) continue;
            foreach ($allProps as $prop2) {
                if ($prop2['parent_id'] !== $prop['id']) continue;
                if (!in_array($prop2['id'], $priceIds)) continue;
                foreach ($allProps as $prop3) {
                    if ($prop3['parent_id'] !== $prop2['id']) continue;
                    if (!in_array($prop3['id'], $priceIds)) continue;
                    foreach ($allProps as $prop4) {
                        if ($prop4['parent_id'] !== $prop3['id']) continue;
                        if (!in_array($prop4['id'], $priceIds)) continue;
                        $prop3['childs'][] = $prop4;
                    }
                    $prop2['childs'][] = $prop3;
                }
                $prop['childs'][] = $prop2;
            }
            if (isset($prop['childs']) && $prop['childs']) {
                $propsTree[] = $prop;
            }
        }

        dd($propsTree);
    }

    public function pricesSection(string $propCode, ?string $propCode2 = null): View
    {

    }

    /**
     * @param string $code
     * @return array
     */
    protected function parseFilterCode(string $code): array
    {
        if (strpos($code, 'clear') !== false) {
            return [];
        }

        return explode('or', str_replace('-or-', 'or', $code));
    }

    /**
     * @param array $ids
     * @return Builder
     */
    protected function getCompanyBeProperty(array $ids = []): Builder
    {
        $result = CompanyProperty::query();

        if (count($ids) > 0) {
            $result->whereIn('property_id', $ids);
        }

        return Company::query()
            ->whereIn('id', $result->pluck('company_id')->toArray())
            ->where('active', true);
    }
}
