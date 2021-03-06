<?php
namespace App\Service\Component;

use App\Models\ServiceMenu as MenuModel;

class Menu extends Base
{
    /**
     * @param array $arParams
     * @return array
     */
    public function prepareParams(array $arParams): array
    {
        $arParams['replace'] = $arParams['replace'] ?? [];

        return parent::prepareParams($arParams);
    }

    /**
     *
     */
    public function execute(): void
    {
        $arData = $this->getCacheData($this->arParams, function() {
            if ($record = MenuModel::query()
                ->where([
                    ["code", $this->arParams["code"]],
                    ['active', true]
                ])
                ->first()) {
                return $record->toArray();
            }
            return null;
        });
        if (is_array($arData) && count($arData) > 0) {
            if (isset($arData["content"])) {
                $arData["items"] = json_decode($arData["content"], true);
                $arData['name'] = str_replace(
                    array_keys($this->arParams["replace"]),
                    array_values($this->arParams["replace"]),
                    $arData['name']
                );

                if (is_array($arData["items"])) {
                    foreach ($arData["items"] as &$item) {
                        $item['link'] = str_replace(
                            array_keys($this->arParams["replace"]),
                            array_values($this->arParams["replace"]),
                            $item['link']
                        );
                        if (strpos($item['link'], '//') === 0) {
                            $item['link'] = substr($item['link'], 1, strlen($item['link']) - 1);
                        }
                    }
                    unset($item);
                }

                unset($arData["content"]);
            }
            $this->arResult = $arData;
        }
    }
}
