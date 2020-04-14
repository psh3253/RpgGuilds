<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BankCommands
{
    /**
     * @var BankCommands
     */
    private static $instance;

    public function bankCommands(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!($sender instanceof Player)) {
            return true;
        }
        ActionManager::getInstance()->openBank($sender->getName());
        return true;
    }

    public static function getInstance() : BankCommands{
        return self::$instance;
    }
}
