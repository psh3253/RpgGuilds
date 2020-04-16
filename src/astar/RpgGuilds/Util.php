<?php

namespace astar\RpgGuilds;

use pocketmine\level\Location;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Util
{
    /**
     * @var Util
     */
    private static $instance;
    public static $stub = TextFormat::GOLD . "BM: " . TextFormat::RESET;

    public static function getPlayer(string $name)
    {
        $onlinePlayers = [];
        for ($length = count($onlinePlayers = Server::getInstance()->getOnlinePlayers()), $i = 0; $i < $length; ++$i) {
            $player = $onlinePlayers[$i];
            if ($player->getDisplayName() === $name) {
                return $player;
            }
        }
        return null;
    }

    public static function sendMessage(Player $player, string $m)
    {
        $player->sendMessage(Util::$stub . $m);
    }

    public static function getInstance(): Util
    {
        return self::$instance;
    }
}