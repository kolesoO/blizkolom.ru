<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyProperty;
use App\Models\File;
use App\Models\Property;
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
     * @return View
     */
    public function index(string $propertyCode = null): View
    {
        $property = Property::query()
            ->where([
                ['code', $propertyCode],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->first(['id', 'title']);

        if (!$property) {
            return $this->section(null, $propertyCode);
        }

        return view($this->getActualPage('index'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => "/" . $propertyCode
                    ]),
                ],
                'root_property_title' => $property->title,
                'property_id' => [$property->id]
            ],
            $this->getHeaderData([
                'menu_url_prefix' => "/" . $propertyCode
            ]),
            $this->getFooterData()
        ));
    }

    /**
     * @param string|null $propertyCode
     * @param string $property2Code
     * @param string|null $property3Code
     * @return View
     */
    public function section(?string $propertyCode, string $property2Code, string $property3Code = null): View
    {
        //fix
        if (!is_null($propertyCode) && !is_null($property2Code) && is_null($property3Code)) {
            $property3Code = $property2Code;
            $property2Code = $propertyCode;
            $propertyCode = null;
        }
        if ($property2Code == 'priem') {
            return $this->company($propertyCode, $property3Code);
        }
        //end

        $rootProperty = Property::query()
            ->where([
                ['code', $propertyCode],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->firstOrFail();

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
        $propsId = [$mainProperty['id']];
        if (isset($innerProperty)) {
            $innerProperty = $innerProperty->toArray();
            $propsId[] = $innerProperty['id'];
        }

        return view($this->getActualPage('section'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => "/" . $propertyCode
                    ]),
                ],
                'property_id' => $propsId,
                'root_property_title' => $rootProperty->title
            ],
            $this->getHeaderData(),
            $this->getFooterData()
        ));
    }

    public function company(?string $propertyCode, string $companyCode): View
    {
        $rootProperty = Property::query()
            ->where([
                ['code', $propertyCode],
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
        }
        if ($image = File::query()->find($company->detail_picture)) {
            $image->path = File::withRemoteDomain($image->path);
            $company->detail_picture = $image;
        }
        //end

        return view($this->getActualPage('company'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => $companyCode,
                        "replace" => [
                            "title" => [
                                "{title}" => $company->title
                            ],
                            "description" => [
                                "{description}" => $company->description
                            ],
                            "keywords" => [
                                "{keywords}" => $company->keywords
                            ],
                        ]
                    ]),
                ],
                'company' => $company,
                'root_property_title' => $rootProperty->title
            ],
            $this->getHeaderData(),
            $this->getFooterData()
        ));
    }

    /**
     * @return View
     */
    public function how(): View
    {
        $property = Property::query()
            ->where([
                ['code', null],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->first(['id', 'title']);

        return view($this->getActualPage('how-to'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => "/kak-sdat"
                    ]),
                ],
                'root_property_title' => $property->title,
                'property_id' => [$property->id]
            ],
            $this->getHeaderData(),
            $this->getFooterData()
        ));
    }

    /**
     * @return View
     */
    public function netochnost(): View
    {
        $property = Property::query()
            ->where([
                ['code', null],
                ['urlable', 1],
                ['root_url', 1]
            ])
            ->first(['id', 'title']);

        return view($this->getActualPage('netochnost'), array_merge(
            [
                "header" => [
                    "seo" => app()->component->includeComponent("Seo", "", [
                        "code" => "/netochnost"
                    ]),
                ],
                'root_property_title' => $property->title,
                'property_id' => [$property->id]
            ],
            $this->getHeaderData(),
            $this->getFooterData()
        ));
    }
}
