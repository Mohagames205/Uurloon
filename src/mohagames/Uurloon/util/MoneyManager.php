<?php


namespace mohagames\Uurloon\util;


class MoneyManager
{

    public static function getAmount(int $level) : int
    {
        return 200 + (5 * $level);
    }

}