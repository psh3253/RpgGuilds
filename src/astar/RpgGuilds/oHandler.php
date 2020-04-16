<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class oHandler implements CommandExecutor
{
    /**
     * @var oHandler
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
        $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
        $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
        if (!isset($this->plugin->config[$player->getName()])) {
            $player->sendMessage("You are not even in a guild? how can you expect to talk in one?");
            return true;
        }
        if (!RpgGuilds::getInstance()->getConfig()->get("Guilds." . $guildn . ".Ranks." . $grank . ".Ochat")) {
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["Ochat"]) {
                $player->sendMessage("You do not have permission to talk in officer chat!");
                return true;
            }
            $buffer = "";
            for ($i = 0; $i < count($args); ++$i) {
                $buffer = $buffer . ' ' . $args[$i];
            }
            $s = (string)$buffer;
            $title = $this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["Title"];
            foreach ($this->plugin->config["Guilds"][$guildn]["Players"] as $key => $value) {
                $prank = $this->plugin->config["Guilds"][$guildn]["Players"][$key]["Rank"];
                if (Server::getInstance()->getPlayer($key) != null) {
                    $p = Server::getInstance()->getPlayer($key);
                    if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$prank]["Ochat"]) {
                        continue;
                    }
                }
                $p->sendMessage("§F[" . $title . "§F]" . $player->getName() . ": §a" . $s);
            }
        }
        return true;
    }
}