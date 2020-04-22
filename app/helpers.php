<?php

declare(strict_types=1);

/**
 * Добавление окончания к строке
 *
 * @param int $value
 * @param array $status
 * @return string
 */
function GetDeclNum(int $value = 1, array $status = ['', 'а', 'ов']): string
{

    $array = array(2, 0, 1, 1, 1, 2);

    return $status[($value % 100 > 4 && $value % 100 < 20) ? 2 : $array[($value % 10 < 5) ? $value % 10 : 5]];

}
