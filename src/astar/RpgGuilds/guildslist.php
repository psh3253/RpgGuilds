<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;

class guildslist implements CommandExecutor
{
    /**
     * @var guildslist
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
        $i = 0;
        foreach ($this->plugin->config["Guilds"] as $guilds => $value1) {
            $i = 0;
            foreach ($this->plugin->config["Guilds"][$guilds]["Players"] as $players => $value2) {
                if ($players != null) {
                    ++$i;
                }
                if ($i == 1) {
                    $player->sendMessage("§e" . $guilds . ": §2" . $i . " 유저.");
                } else {
                    $player->sendMessage("§e" . $guilds . ": §2" . $i . " 유저들.");
                }
            }
        }
        return true;
    }
}
