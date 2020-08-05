<?php


namespace mohagames\Uurloon\task;

use mohagames\Uurloon\util\MoneyManager;
use pocketmine\scheduler\Task;

class ResetLimitTask extends Task
{

    public function onRun(int $currentTick)
    {

        if(date('d') != MoneyManager::$lastPurged)
        {
            MoneyManager::$lastPurged = date('d');
            MoneyManager::resetLimit();
        }

    }
}