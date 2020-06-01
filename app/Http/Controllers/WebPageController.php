<?php

namespace App\Http\Controllers;

class WebPageController extends Controller
{
    /**
     * @param $inputName
     * @return string
     */
    public function getWithDevicePrefix($inputName): string
    {
        /** @see \Mobile_Detect */
        if (app()->deviceChecker->isMobile()) {
            $inputName .= "-mobile";
        }

        return $inputName;
    }

    /**
     * @param $inputName
     * @return string
     */
    public function getActualPage($inputName): string
    {
        /** @see \Mobile_Detect */
        if (app()->deviceChecker->isMobile()) {
            $inputName = "mobile.".$inputName;
        } else {
            $inputName = "desktop.".$inputName;
        }

        return "pages.".$inputName;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getHeaderData(array $params = []): array
    {
        return [
            'menu' => [
                'take' => app()->component->includeComponent(
                    "Menu",
                    'default',
                    array_merge(["code" => "take"], $params)
                ),
                'to_points' => app()->component->includeComponent(
                    "Menu",
                    'default',
                    array_merge(["code" => "to_points"], $params)
                ),
                'more' => app()->component->includeComponent(
                    "Menu",
                    'default',
                    array_merge(["code" => "more"], $params)
                ),
                'general' => app()->component->includeComponent(
                    "Menu",
                    $this->getWithDevicePrefix('general'),
                    array_merge(["code" => "general"], $params)
                ),
            ]
        ];
    }

    /**
     * @return array
     */
    public function getFooterData():array
    {
        return [
            "copyright" => app()->component->includeComponent("SingleText", "default", [
                "code" => "copyright"
            ])
        ];
    }
}
