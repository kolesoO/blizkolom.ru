<?php

namespace App\Service\Component;

abstract class Base
{
    /**
     * @var null
     */
    protected $arResult = null;

    /**
     * @var null
     */
    protected $arParams = null;

    /**
     * @var int
     */
    protected $limitRows = 20;

    /**
     * @param array $arParams
     * @return array
     */
    public function prepareParams(array $arParams): array
    {
        $arParams["component_name"] = get_class($this);
        $arParams["filter"] = isset($arParams["filter"]) && is_array($arParams["filter"]) ? $arParams["filter"] : [];
        if (!isset($arParams["limit"])) {
            $arParams["limit"] = $this->limitRows;
        }
        if (!isset($arParams["show_404"])) {
            $arParams["show_404"] = 0;
        }

        return $arParams;
    }

    /**
     *
     */
    abstract function execute(): void;

    /**
     * @return mixed
     */
    function getResult()
    {
        return $this->arResult;
    }

    /**
     * @return mixed
     */
    function getParams()
    {
        return $this->arParams;
    }

    /**
     * @param $arParams
     */
    function setParams($arParams): void
    {
        $this->arParams = $arParams;
    }

    /**
     * @param array $params
     * @param callable $processFunction
     * @param int $cacheTime
     * @return mixed
     */
    protected function getCacheData(array $params, callable $processFunction, int $cacheTime = 0)
    {
        return app()
            ->cacheSystem
            ->resource::get($params, $processFunction, $cacheTime);
    }

    /**
     * @param string $code
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    protected function getPageUrl(string $code, string $prefix = "", string $suffix = ""): string
    {
        $return = "/" . $code . "/";
        if (strlen($prefix) > 0) {
            $return = $prefix . $return;
        }
        if (strlen($suffix) > 0) {
            $return .= $suffix . "/";
        }
        return $return;
    }
}
