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
    private static $instance = null;

    /**
     * @var RpgGuilds
     */
    private $plugin;

    public function __construct(RpgGuilds $plugin)
    {
        if(self::$instance == null)
            self::$instance = $this;

        $this->plugin = $plugin;
    }

    public function bankCommands(CommandSender $sender, array $args): bool
    {
        if (!($sender instanceof Player)) {
            return true;
        }
        ActionManager::getInstance()->openBank($sender->getName());
        return true;
    }

    public static function getInstance(): BankCommands
    {
        return self::$instance;
    }
}
