<?php


namespace mohagames\uurloon\task;


use mohagames\LevelAPI\utils\LevelManager;
use mohagames\Uurloon\Main;
use mohagames\Uurloon\util\MoneyManager;
use onebone\economyapi\EconomyAPI;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class GivePlayerMoneyTask extends Task
{

    /**
     * @var Player
     */
    private $player;
    /**
     * @var EconomyAPI|null
     */
    private $eco;

    /**
     * @var LevelManager
     */
    private $lvl;

    public function __construct(Player $player)
    {
        $this->player = $player;
        $this->eco = EconomyAPI::getInstance();
        $this->lvl = LevelManager::getManager();
    }

    public function onRun(int $currentTick)
    {
        if($this->player->isOnline())
        {
            $amount = MoneyManager::getAmount($this->lvl->getLevel($this->player->getName()));
            $this->eco->addMoney($this->player, $amount);
            $this->player->sendMessage("§f[§bTDB-Uurloon§f] §bU heeft uw uurloon van $amount euro ontvangen!");
            return;
        }

        Main::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        unset(Main::getInstance()->tasks[$this->player->getName()]);

    }

}