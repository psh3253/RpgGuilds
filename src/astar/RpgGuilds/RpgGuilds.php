<?php

namespace astar\RpgGuilds;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class RpgGuilds extends PluginBase implements Listener
{
    static $plugin;
    public static $wgPlugin;
    public $gbanks = [];

    public function onEnable()
    {
        $plugin = $this;
        $this->getCommand("guild")->setExecutor(new GuildHandler($this));
        $this->getCommand("g")->setExecutor(new gcHandler($this));
        $this->getCommand("o")->setExecutor(new oHandler($this));
        $this->getCommand("gtp")->setExecutor(new gTP($this));
        $this->getCommand("guilds")->setExecutor(new guildslist($this));
        $this->getCommand("lookup")->setExecutor(new lookUpCommand($this));
        $this->getCommand("hq")->setExecutor(new hqCommand($this));
        $this->getCommand("grank")->setExecutor(new gRankHandler($this));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("RpgGuilds has been enabled!");
        $this->getServer()->getPluginManager()->registerEvents(new InventoryListener(), $this);

        if ($this->getServer()->getPluginManager()->isPluginEnabled("WorldGuard")) {
            $wgPlugin = $this->getServer()->getPluginManager()->getPlugin("WorldGuard");
            $this->getLogger()->info(TextFormat::RED . "[BankManager] Hooked Onto WorldGuard");
        }
        $this->saveDefaultConfig();
        if (!$this->getConfig()->exists("TP")) {
            $this->getConfig()->set("TP", (Object)false);
            $this->saveConfig();
        }
        if (!$this->getConfig()->exists("Guild Names in Chat")) {
            $this->getConfig()->set("Guild Names in Chat", true);
            $this->saveConfig();
        }
        if (!$this->getConfig()->exists("Chat")) {
            $this->getConfig()->set("Chat", true);
            $this->saveConfig();
        }
        if (!$this->getConfig()->exists("No Build")) {
            $this->getConfig()->set("No Build", true);
            $this->saveConfig();
        }
        if (!$this->setupEconomy()) {
            $this->getLogger()->alert("[%s] - Disabled due to no EconomyAPI plugin found!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    private function setupEconomy(): bool
    {
        if ($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") == null) {
            return false;
        }
        $econ = EconomyAPI::getInstance();
        return $econ != null;
    }

    public function onDisable() {
        $this->getLogger()->info("RpgGuilds has been Disabled!");
        $this->saveConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($label === "gbank") {
            $player = $sender;
            $gbanks->add($player->getName());
            return BankCommands::getInstance()->bankCommands($sender, $args);
        }
        return false;
    }


}