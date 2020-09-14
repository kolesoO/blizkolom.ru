<?php

namespace App\Service\Component;

use App\Models\Property;
use App\Service\Helpers\BuilderHelper;
use Illuminate\Support\Facades\Log;

class PropertyList extends Base
{
    /**
     * @param array $arParams
     * @return array
     */
    public function prepareParams(array $arParams): array
    {
        $arParams['root'] = intval($arParams['root'] ?? 0);

        return parent::prepareParams($arParams);
    }

    /**
     *
     */
    public function execute(): void
    {
        if ($this->arParams['root'] > 0) {
            $this->arResult['root'] = $this->getCacheData(
                array_merge($this->arParams, ['root_prop' => $this->arParams['root']]),
                function () {
                    return Property::query()
                        ->find($this->arParams['root']);
                }
            );
        }

        if (isset($this->arParams['parent_code'])) {
            $this->arResult['parent'] = $this->getCacheData(
                array_merge($this->arParams, ['parent_prop' => $this->arParams['parent_code']]),
                function () {
                    /** @var Property $parent */
                    $parent = Property::query()
                        ->where('code', $this->arParams['parent_code'])
                        ->first();

                    $url = [$parent->code];

                    if (isset($this->arResult['root']) && !is_null($this->arResult['root']->code)) {
                        array_unshift($url, $this->arResult['root']->code);
                    }

                    $parent->url = '/' . implode('/', $url);

                    $parent->seo = app()->component->includeComponent("Seo", "", [
                        "code" => "/{root_code}/" . $parent->code,
                        "replace" => [
                            'menu_title' => [
                                "{genetiv}" => $this->arResult['root']->genetiv,
                                "{gdetiv}" => $this->arResult['root']->gdetiv,
                                "{nominativ}" => $this->arResult['root']->nominativ,
                            ],
                        ]
                    ]);

                    return $parent;
                }
            );
        }

        $this->arResult['items'] = $this->getCacheData($this->arParams, function () {
            $filter = $this->arParams['filter'];

            if ($this->arResult['parent']) {
                $filter['parent_id'] = $this->arResult['parent']->id;
            }

            $items = BuilderHelper::getFiltered(
                Property::query(),
                $filter
            );

            return $items
                ->get()
                ->each(function (Property $item) {
                    $url = [$item->code];

                    if (isset($this->arResult['parent'])) {
                        array_unshift($url, $this->arResult['parent']->code);
                    }

                    if (isset($this->arResult['root']) && !is_null($this->arResult['root']->code)) {
                        array_unshift($url, $this->arResult['root']->code);
                    }

                    $item->url = '/' . implode('/', $url);

                    return $item;
                })
                ->all();
        });
    }
}
