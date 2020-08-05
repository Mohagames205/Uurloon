<?php

declare(strict_types=1);

namespace mohagames\Uurloon;

use mohagames\uurloon\task\GivePlayerMoneyTask;
use mohagames\Uurloon\task\ResetLimitTask;
use mohagames\Uurloon\util\MoneyManager;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private static $instance;
    public $tasks;
    private $interval;



    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $default = [
            "interval" => 20 * 60 * 60,
            "daglimiet" => 6,
            "formule" => "werkt nog niet"
        ];
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML, $default);
        $config->save();

        date_default_timezone_set('Europe/Brussels');
        MoneyManager::$lastPurged = (int) date("d");
        MoneyManager::$dailyLimit = $config->get("daglimiet");
        $this->interval = $config->get("interval");

        $this->getScheduler()->scheduleRepeatingTask(new ResetLimitTask(), 2000);


        self::$instance = $this;
    }

    public function onJoin(PlayerJoinEvent $e)
    {
        MoneyManager::initLimit($e->getPlayer());
        $task = $this->getScheduler()->scheduleDelayedRepeatingTask(new GivePlayerMoneyTask($e->getPlayer()), $this->interval, $this->interval);
        $this->tasks[$e->getPlayer()->getName()] = $task->getTaskId();
    }

    public function onLeave(PlayerQuitEvent $e)
    {
        $this->getScheduler()->cancelTask($this->tasks[$e->getPlayer()->getName()]);
        unset($this->tasks[$e->getPlayer()->getName()]);
    }

    public static function getInstance() : self
    {
        return self::$instance;
    }


}
