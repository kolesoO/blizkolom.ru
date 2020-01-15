<?php
namespace App\Service\Component;

use App\Models\ServiceSeo;

class Seo extends Base
{
    /**
     * @param array $arParams
     * @return array
     */
    public function prepareParams(array $arParams): array
    {
        $arParams["replace"] = isset($arParams["replace"]) && is_array($arParams["replace"]) ? $arParams["replace"] : [];
        return parent::prepareParams($arParams);
    }

    /**
     *
     */
    public function execute(): void
    {
        $this->arResult = $this->getCacheData($this->arParams, function() {
            if ($record = ServiceSeo::query()
                ->where("page_url", "=", $this->arParams["code"])
                ->first()) {
                foreach ($this->arParams["replace"] as $fieldCode => $replaceRules) {
                    if (!isset($record->$fieldCode)) continue;
                    $record->$fieldCode = str_replace(array_keys($replaceRules), array_values($replaceRules), $record->$fieldCode);
                }
                return $record->toArray();
            }
            return null;
        });
    }
}