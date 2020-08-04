<?php


namespace mohagames\Uurloon\util;


use pocketmine\Player;

class MoneyManager
{

    public static $limit;
    public static $lastPurged;

    public static $dailyLimit = 6;

    public static function getAmount(int $level) : int
    {
        return 200 + (5 * $level);
    }

    public static function getLimit(Player $player)
    {
        return self::$limit[$player->getName()];
    }

    public static function isOverLimit(Player $player)
    {
        return self::$limit[$player->getName()] > self::$dailyLimit;
    }

    public static function initLimit(Player $player)
    {
        if(!isset(self::$limit[$player->getName()])) {
            self::$limit[$player->getName()] = 0;
        }
    }

    public static function setPlayerLimit(Player $player, int $value)
    {
        self::$limit[$player->getName()] = $value;
    }

    public static function resetLimit()
    {
        foreach(self::$limit as $key => $limit)
        {
            self::$limit[$key] = 0;
        }
    }

    public static function incrementLimit(Player $player)
    {
        self::$limit[$player->getName()]++;
    }

    public static function unsetLimit(Player $player)
    {
        if(isset(self::$limit[$player->getName()])) unset(self::$limit[$player->getName()]);
    }



}