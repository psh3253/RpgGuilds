<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class gcHandler implements CommandExecutor
{
    /**
     * @var gcHandler
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
        $player = $sender;
        if (!isset($this->plugin->config[$player->getName()])) {
            $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
            return true;
        }
        $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
        $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
        if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["Gchat"]) {
            $player->sendMessage("§d[ §f길드 §d] §f이 길드에서 대화 할 권한이 없습니다!");
            return true;
        }
        $buffer = "";
        for ($i = 0; $i < count($args); ++$i) {
            $buffer = $buffer . " " . $args[$i];
        }
        $s = (string)$buffer;
        $title = $this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["Title"];
        foreach ($this->plugin->config["Guilds"][$guildn]["Players"] as $key => $value) {
            if (Server::getInstance()->getPlayer($key) != null) {
                $p = Server::getInstance()->getPlayer($key);
                $p->sendMessage("§d[ " . $title . " §d] §a" . $player->getName() . " :" . $s);
            }
        }
        return true;
    }
}
