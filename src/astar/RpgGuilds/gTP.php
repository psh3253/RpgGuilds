<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;

class gTP implements CommandExecutor
{
    /**
     * @var gTP
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

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $player = $sender;
        if (!$player->hasPermission("guild.teleport")) {
            $player->sendMessage("You do not have permission to teleport people on this server!");
            return true;
        }
        if (count($args) == 0) {
            return false;
        }
        if (!$this->plugin->config["TP"]) {
            $player->sendMessage("Teleporting guild members is disabled on this server!");
            return true;
        }
        if (Server::getInstance()->getPlayer($args[0]) == null) {
            $player->sendMessage("That player cannot be found");
            return true;
        }
        $p = Server::getInstance()->getPlayer($args[0]);
        $pi = $p->getName();
        if (!$this->plugin->getConfig()->exists($pi)) {
            $player->sendMessage("This player is not even in a guild!");
            return true;
        }
        if (!$this->plugin->getConfig()->exists($player->getName())) {
            $player->sendMessage("You are not even in a guild? how can you invite someone?");
            return true;
        }
        $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
        $guildn2 = $this->plugin->config[$pi]["Guild"]["Name"];
        $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];

        if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["TP"]) {
            $player->sendMessage("You do not have permission to teleport people in this guild!");
            return true;
        }
        if (!$guildn === $guildn2) {
            $player->sendMessage("This player is not in your guild, you can only teleport members of your own guild!");
            return true;
        }
        if(!$player instanceof Player)
        {
            return true;
        }
        $p->teleport($player);
        return true;
    }
}