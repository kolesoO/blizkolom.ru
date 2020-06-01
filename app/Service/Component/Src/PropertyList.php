<?php

namespace App\Service\Component;

use App\Models\Property;
use App\Service\Helpers\BuilderHelper;

class PropertyList extends Base
{
    /**
     * @param array $arParams
     * @return array
     */
    public function prepareParams(array $arParams): array
    {
        return parent::prepareParams($arParams);
    }

    /**
     *
     */
    public function execute(): void
    {
        $this->arResult = $this->getCacheData($this->arParams, function () {
            $items = Property::query();


            if (isset($this->arParams['filter']) && is_array($this->arParams['filter'])) {
                $items = BuilderHelper::getFiltered($items, $this->arParams['filter']);
            }

            return $items->get()->all();
        });
    }
}
