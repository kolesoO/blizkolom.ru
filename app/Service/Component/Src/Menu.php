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
        $arParams['menu_url_prefix'] = $arParams['menu_url_prefix'] == '/' ? '' : $arParams['menu_url_prefix'];

        return parent::prepareParams($arParams);
    }

    /**
     *
     */
    public function execute(): void
    {
        $arData = $this->getCacheData($this->arParams, function() {
            if ($record = MenuModel::query()
                ->where("code", "=", $this->arParams["code"])
                ->first()) {
                return $record->toArray();
            }
            return null;
        });
        if (is_array($arData) && count($arData) > 0) {
            if (isset($arData["content"])) {
                $arData["items"] = json_decode($arData["content"], true);
                if (is_array($arData["items"])) {
                    foreach ($arData["items"] as &$item) {
                        $item['link'] = $this->arParams['menu_url_prefix'] . $item['link'];
                    }
                    unset($item);
                }
                unset($arData["content"]);
            }
            $this->arResult = $arData;
        }
    }
}
