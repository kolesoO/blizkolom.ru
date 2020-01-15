<?php
namespace App\Service\Component;

use App\Models\SingleText as SingleTextModel;

class SingleText extends Base
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
        $this->arResult = $this->getCacheData($this->arParams, function() {
            if ($record = SingleTextModel::query()
                ->where("code", "=", $this->arParams["code"])
                ->first()) {
                return $record->toArray();
            }
            return null;
        });
    }
}