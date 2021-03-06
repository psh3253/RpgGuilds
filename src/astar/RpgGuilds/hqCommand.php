<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\level\Location;
use pocketmine\Player;
use pocketmine\Server;

class hqCommand implements CommandExecutor
{
    /**
     * @var hqCommand
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
        if (count($args) <= 0) {
            return false;
        }
        if ($args[0] === "설정") {
            $player = $sender;
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            $leader = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["길드마스터"];
            if ($grank !== $leader) {
                $player->sendMessage("§d[ §f길드 §d] §f길드 리더만이 길드본부를 설정할 수 있습니다!");
                return true;
            }
            if (!$player instanceof Entity) {
                return true;
            }
            $loc = $player->getLocation();
            foreach ($this->plugin->config["Guilds"] as $guild => $value) {
                if (isset($this->plugin->config["Guilds"][$guild]["HQ"])) {
                    $X1 = $this->plugin->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->plugin->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->plugin->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->plugin->config["Guilds"][$guild]["HQ"]["World"];
                    if ($player->getLevel()->getName() !== $world1) {
                        continue;
                    }
                    $gloc = new Location($X1, $Y1, $Z1, 0, 0, Server::getInstance()->getLevelByName($world1));
                    if ($loc->distance($gloc) <= 100.0) {
                        $player->sendMessage("§d[ §f길드 §d] §f이곳은 " . $guild . "의 길드본부와 너무 가깝습니다!");
                        return true;
                    }
                    continue;
                }
            }
            $X2 = $loc->getX();
            $Y2 = $loc->getY();
            $Z2 = $loc->getZ();
            $world2 = $loc->getLevel()->getName();
            $this->plugin->config["Guilds"][$guildn]["HQ"]["X"] = $X2;
            $this->plugin->config["Guilds"][$guildn]["HQ"]["Y"] = $Y2;
            $this->plugin->config["Guilds"][$guildn]["HQ"]["Z"] = $Z2;
            $this->plugin->config["Guilds"][$guildn]["HQ"]["World"] = $world2;
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            $player->sendMessage("§d[ §f길드 §d] §f길드본부가 성공적으로 저장되었습니다!");
            return true;
        } else {
            if ($args[0] !== "tp") {
                return false;
            }
            $player = $sender;
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guild2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            if (!isset($this->plugin->config["Guilds"][$guild2]["HQ"])) {
                $player->sendMessage("§d[ §f길드 §d] §f당신의 길드가 길드본부를 아직 설정하지 않았습니다!");
                return true;
            }
            $X3 = $this->plugin->config["Guilds"][$guild2]["HQ"]["X"];
            $Y3 = $this->plugin->config["Guilds"][$guild2]["HQ"]["Y"];
            $Z3 = $this->plugin->config["Guilds"][$guild2]["HQ"]["Z"];
            $world3 = $this->plugin->config["Guilds"][$guild2]["HQ"]["World"];
            $gloc2 = new Location($X3, $Y3, $Z3, 0, 0, Server::getInstance()->getLevelByName($world3));
            if (!$player instanceof Entity) {
                return true;
            }
            $ploc = $player->getLocation();
            $p = $player;
            if (!$p instanceof Player) {
                return true;
            }
            $this->plugin->getScheduler()->scheduleDelayedRepeatingTask(new HQTeleportTask($this->plugin, $p, $gloc2, $ploc), 0, 20);
            return true;
        }
    }
}