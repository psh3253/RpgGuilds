<?php

namespace astar\RpgGuilds;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class lookUpCommand implements CommandExecutor
{
    /**
     * @var lookUpCommand
     */
    private static $instance = null;

    /**
     * @var RpgGuilds
     */
    private $plugin;

    public function __construct(RpgGuilds $plugin)
    {
        if (self::$instance == null)
            self::$instance = $this;

        $this->plugin = $plugin;
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $p = $sender;
        if (count($args) != 1) {
            $p->sendMessage("Improper usage! Please use /lookup {Player name}");
            return true;
        }
        if (Server::getInstance()->getPlayer($args[0]) == null) {
            $p->sendMessage("This player could not be found");
            return true;
        }
        $player = Server::getInstance()->getPlayer($args[0]);
        $money = EconomyAPI::getInstance()->myMoney($player->getName());
        $kills = $this->plugin->config["Kills"][$player->getName()];
        $p->sendMessage("§APlayer Lookup: §e" . $player->getName());
        if (isset($this->plugin->config[$player->getName()])) {
            $guild = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $p->sendMessage("§APlayer Lookup: §5Guild - " . $guild);
        }
        $p->sendMessage("§APlayer Lookup: §4Kills - " . $kills);
        $p->sendMessage("§APlayer Lookup: §2Money - " . $money);
        return true;
    }
}