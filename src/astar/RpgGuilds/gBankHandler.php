<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

class gBankHandler implements CommandExecutor
{
    private $Rpgg;

    public function gBankHandler(RpgGuilds $rpgg)
    {
        $this->Rpgg = $rpgg;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        return false;
    }
}
