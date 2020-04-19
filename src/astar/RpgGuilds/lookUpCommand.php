<?php

namespace astar\RpgGuilds;

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
            $p->sendMessage("§d[ §f길드 §d] §f잘못된 사용법 입니다! /조회 <유저>을 사용하세요!");
            return true;
        }
        if (Server::getInstance()->getPlayer($args[0]) == null) {
            $p->sendMessage("§d[ §f길드 §d] §f해당 유저를 찾을 수 없습니다!");
            return true;
        }
        $player = Server::getInstance()->getPlayer($args[0]);
        $kills = $this->plugin->config["Kills"][$player->getName()];
        $p->sendMessage("§d[ §f길드 §d] §f유저 조회: §e" . $player->getName());
        if (isset($this->plugin->config[$player->getName()])) {
            $guild = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $p->sendMessage("§d[ §f길드 §d] §f유저 조회: §e길드 - " . $guild ." §c죽인횟수 - " . $kills);
            return true;
        }
        $p->sendMessage("§d[ §f길드 §d] §f유저 조회: §c죽인횟수 - " . $kills);
        return true;
    }
}