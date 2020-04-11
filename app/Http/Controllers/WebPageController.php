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
                'take' => app()->component->includeComponent("Menu", 'default', [
                    "code" => "take",
                    'menu_url_prefix' => $params['menu_url_prefix'] ?? ''
                ]),
                'to_points' => app()->component->includeComponent("Menu", 'default', [
                    "code" => "to_points",
                    'menu_url_prefix' => $params['menu_url_prefix'] ?? ''
                ]),
                'more' => app()->component->includeComponent("Menu", 'default', [
                    "code" => "more",
                    'menu_url_prefix' => $params['menu_url_prefix'] ?? ''
                ]),
                'general' => app()->component->includeComponent("Menu", $this->getWithDevicePrefix('general'), [
                    "code" => "general",
                    'menu_url_prefix' => $params['menu_url_prefix'] ?? ''
                ]),
            ]
        ];
    }

    /**
     * @return array
     */
    public function getFooterData():array
    {
        return [
            "footer" => [
                "copyright" => app()->component->includeComponent("SingleText", "default", [
                    "code" => "copyright"
                ])
            ],
        ];
    }
}
