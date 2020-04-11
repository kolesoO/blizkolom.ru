<?php

namespace App\Service\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BuilderHelper
{
    /**
     * @param Builder $builder
     * @param array $filter
     * @return Builder
     */
    public static function getFiltered(Builder $builder, array $filter): Builder
    {
        foreach ($filter as $key => $value) {
            $operator = self::getOperator($key, $value);
            if (is_array($value)) {
                foreach ($value as $valueItem) {
                    $builder = $builder->orWhere($key, $operator, $valueItem);
                }
            } else {
                $builder = $builder->where($key, $operator, $value);
            }
        }

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param array $order
     * @return Builder
     */
    public static function getOrdered(Builder $builder, array $order): Builder
    {
        if (isset($order["order"])) {
            if ($order["order"] == "asc") {
                $builder = $builder->orderBy($order["by"]);
            } else {
                $builder = $builder->orderByDesc($order["by"]);
            }
        }

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param int $limit
     * @return Builder
     */
    public static function getLimited(Builder $builder, int $limit): Builder
    {
        return $builder->limit($limit);
    }

    /**
     * @param Builder $builder
     * @param array $select
     * @return Builder
     */
    public static function getSelected(Builder $builder, array $select): Builder
    {
        if (count($select) > 0) {
            $newSelect = [];
            foreach ($select as $selectItem) {
                $resultItem = is_array($selectItem) ? $selectItem["table"].".".$selectItem["field"] : $selectItem;
                if (isset($selectItem["name"])) {
                    $resultItem .= " as ".$selectItem["name"];
                }
                $newSelect[] = $resultItem;
            }
            return $builder->select($newSelect);
        }

        return $builder;
    }

    /**
     * @param Builder $builder
     * @param array $joinData
     * @return Builder
     */
    public static function getJoined(Builder $builder, array $joinData): Builder
    {
        foreach ($joinData as $joinInfo) {
            if (!isset($joinInfo["table"]) || !isset($joinInfo["first"]) || !isset($joinInfo["operator"]) || !isset($joinInfo["second"]) || !isset($joinInfo["type"])) continue;
            $builder = $builder->join($joinInfo["table"], $joinInfo["first"], $joinInfo["operator"], $joinInfo["second"], $joinInfo["type"]);
        }

        return $builder;
    }

    /**
     * Обновление на основе данных из Request
     *
     * @param Builder $rsBuilder
     * @param Request $request
     * @param int $defLimit
     * @return Builder
     */
    public static function getUpdated(Builder $rsBuilder, Request $request, int $defLimit): Builder
    {
        $rsBuilder = self::getFiltered($rsBuilder, $request->get("filter", []));
        $rsBuilder = self::getOrdered($rsBuilder, $request->get("sort", []));
        $rsBuilder = self::getLimited($rsBuilder, intval($request->get("limit", $defLimit)));
        $rsBuilder = self::getSelected($rsBuilder, $request->get("select", []));

        return $rsBuilder;
    }

    /**
     * @param string $key
     * @param $value
     * @return string
     */
    private static function getOperator(string &$key, &$value): string
    {
        $startPos = 0;
        $return = "=";
        if ($key[0] == "!") {
            $return = "!=";
            $startPos = 1;
        } elseif ($key[0] == "%") {
            $return = "like";
            $startPos = 1;
            $value = "%".$value."%";
        } else if (in_array($key[0].$key[1], ["<=", ">="])) {
            $return = $key[0].$key[1];
            $startPos = 2;
        } elseif (in_array($key[0], ["<", ">"])) {
            $return = $key[0];
            $startPos = 1;
        }
        $key = substr($key, $startPos, strlen($key) - $startPos);

        return $return;
    }
}