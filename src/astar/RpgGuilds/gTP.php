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
            $player->sendMessage("이 서버에서 유저들을 순간이동할 권한이 없습니다!");
            return true;
        }
        if (count($args) == 0) {
            return false;
        }
        if (!$this->plugin->config["TP"]) {
            $player->sendMessage("이 서버에서 길드원 순간이동이 비활성화 되어있습니다!");
            return true;
        }
        if (Server::getInstance()->getPlayer($args[0]) == null) {
            $player->sendMessage("해당 유저를 찾을 수 없습니다!");
            return true;
        }
        $p = Server::getInstance()->getPlayer($args[0]);
        $pi = $p->getName();
        if (!$this->plugin->getConfig()->exists($pi)) {
            $player->sendMessage("해당 유저는 아직 길드가 없습니다!");
            return true;
        }
        if (!$this->plugin->getConfig()->exists($player->getName())) {
            $player->sendMessage("가입된 길드가 존재하지 않습니다!");
            return true;
        }
        $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
        $guildn2 = $this->plugin->config[$pi]["Guild"]["Name"];
        $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];

        if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["TP"]) {
            $player->sendMessage("이 길드에서 유저들을 순간이동할 권한이 없습니다!");
            return true;
        }
        if (!$guildn === $guildn2) {
            $player->sendMessage("해당 유저는 당신의 길드에 없습니다, 자신의 길드원만 순간이동할 수 있습니다!");
            return true;
        }
        if(!$player instanceof Player)
        {
            return true;
        }
        $this->plugin->teleport($player, $p);
        return true;
    }
}