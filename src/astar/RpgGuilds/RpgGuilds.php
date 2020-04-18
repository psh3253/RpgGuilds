<?php

namespace astar\RpgGuilds;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Arrow;
use pocketmine\level\Location;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class RpgGuilds extends PluginBase implements Listener
{
    /**
     * @var RpgGuilds
     */
    private static $instance;

    /**
     * @var array
     */
    public $config = [];

    public function onEnable()
    {
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

        $this->saveDefaultConfig();
        $this->config = $this->getConfig()->getAll();
        if (!isset($this->config["TP"])) {
            $this->config["TP"] = false;
            $this->saveConfig();
        }
        if (!isset($this->config["Guild Names in Chat"])) {
            $this->config["Guild Names in Chat"] = true;
            $this->saveConfig();
        }
        if (!isset($this->config["Chat"])) {
            $this->config["Chat"] = true;
            $this->saveConfig();
        }
        if (!isset($this->config["No Build"])) {
            $this->config["No Build"] = true;
            $this->saveConfig();
        }
    }

    public function onDisable()
    {
        $this->getLogger()->info("RpgGuilds has been Disabled!");
        $this->saveConfig();
    }

    public function guildBreak(BlockBreakEvent $event)
    {
        $p = $event->getPlayer();
        if ($this->config["No Build"]) {
            $loc = $p->getLocation();
            foreach ($this->config["Guilds"] as $guild => $value) {
                if ($this->config["Guilds"][$guild]["HQ"] != null) {
                    $X1 = $this->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->config["Guilds"][$guild]["HQ"]["World"];
                    if (!$p->getLevel()->getName() === $world1) {
                        continue;
                    }
                    $gloc = new Location(Server::getInstance()->getLevel($world1), $X1, $Y1, $Z1);
                    if ($loc->distance($gloc) > 50.0) {
                        continue;
                    }
                    if (!isset($this->config[$p->getName()])) {
                        $p->sendMessage("You cannot build in the headquarters of " . $guild . ".");
                        $event->setCancelled(true);
                        return;
                    }
                    if (!$this->config[$p->getName()]["Guild"]["Name"] === $guild) {
                        $p->sendMessage("You cannot build in the headquarters of " . $guild . ".");
                        $event->setCancelled(true);
                        return;
                    }
                    continue;
                }
            }
        }
    }

    public function guildUse1(PlayerBucketEmptyEvent $event)
    {
        $p = $event->getPlayer();
        if ($this->config["No Build"]) {
            $loc = $p->getLocation();
            foreach ($this->config["Guilds"] as $guild => $value) {
                if ($this->config["Guilds"][$guild]["HQ"] != null) {
                    $X1 = $this->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->config["Guilds"][$guild]["HQ"]["World"];
                    if (!$p->getLevel()->getName() === $world1) {
                        continue;
                    }
                    $gloc = new Location(Server::getInstance()->getLevel($world1), $X1, $Y1, $Z1);
                    if ($loc->distance($gloc) > 50.0) {
                        continue;
                    }
                    if (!isset($this->config[$p->getName()])) {
                        $p->sendMessage("You cannot build in the headquarters of " . $guild . ".");
                        $event->setCancelled(true);
                        return;
                    }
                    if (!$this->config[$p->getName()]["Guild"]["Name"] === $guild) {
                        $p->sendMessage("You cannot build in the headquarters of " . $guild . ".");
                        $event->setCancelled(true);
                        return;
                    }
                    continue;
                }
            }
        }
    }

    public function guildUse2(PlayerBucketFillEvent $event)
    {
        $p = $event->getPlayer();
        if ($this->config["No Build"]) {
            $loc = $p->getLocation();
            foreach ($this->config["Guilds"] as $guild => $value) {
                if ($this->config["Guilds"][$guild]["HQ"] != null) {
                    $X1 = $this->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->config["Guilds"][$guild]["HQ"]["World"];
                    if (!$p->getLevel()->getName() === $world1) {
                        continue;
                    }
                    $gloc = new Location(Server::getInstance()->getLevel($world1), $X1, $Y1, $Z1);
                    if ($loc->distance($gloc) > 50.0) {
                        continue;
                    }
                    if (!isset($this->config[$p->getName()])) {
                        $p->sendMessage("You cannot build in the headquarters of " . $guild . ".");
                        $event->setCancelled(true);
                        return;
                    }
                    if (!$this->config[$p->getName()]["Guild"]["Name"] === $guild) {
                        $p->sendMessage("You cannot build in the headquarters of " . $guild . ".");
                        $event->setCancelled(true);
                        return;
                    }
                    continue;
                }
            }
        }
    }

    public function guildPlace(BlockPlaceEvent $event)
    {
        $p = $event->getPlayer();
        if ($this->config["No Build"]) {
            $loc = $p->getLocation();
            foreach ($this->config["Guilds"] as $guild => $value) {
                if ($this->config["Guilds"][$guild]["HQ"] != null) {
                    $X1 = $this->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->config["Guilds"][$guild]["HQ"]["World"];
                    if (!$p->getLevel()->getName() === $world1) {
                        continue;
                    }
                    $gloc = new Location(Server::getInstance()->getLevel($world1), $X1, $Y1, $Z1);
                    if ($loc->distance($gloc) > 50.0) {
                        continue;
                    }
                    if (!isset($this->config[$p->getName()])) {
                        $p->sendMessage("You cannot build in the headquarters of " . $guild . ".");
                        $event->setCancelled(true);
                        return;
                    }
                    if (!$this->config[$p->getName()]["Guild"]["Name"] === $guild) {
                        $p->sendMessage("You cannot build in the headquarters of " . $guild . ".");
                        $event->setCancelled(true);
                        return;
                    }
                    continue;
                }
            }
        }
    }

    public function onPlayerDeath(PlayerDeathEvent $event)
    {
        $p = $event->getEntity();
        $lastDmg = $p->getLastDamageCause();
        if ($lastDmg instanceof EntityDamageByEntityEvent) {
            $pl = $lastDmg->getDamager();
        }
        if ($pl instanceof Player) {
            $kills = $this->config["Kills"][$pl->getname()];
            ++$kills;
            $this->config["Kills"][$pl->getName()] = $kills;
        }
    }

    public function onPlayerDmg(EntityDamageByEntityEvent $event)
    {
        if ($event->getEntity() instanceof Player) {
            $a = $event->getEntity();
            $b = null;
            if ($event->getDamager() instanceof Player) {
                $b = $event->getDamager();
            }
            if ($event instanceof EntityDamageByChildEntityEvent) {
                $child = $event->getChild();
            }
            if ($child instanceof Arrow && $event->getDamager() instanceof Player) {
                $b = $event->getDamager();
            }
            if ($b != null && isset($this->config[$a->getName()]) && isset($this->config[$b->getName()])) {
                $guild = $this->config[$a->getName()]["Guild"]["Name"];
                $guild2 = $this->config[$b->getName()]["Guild"]["Name"];
                if ($guild === $guild2) {
                    $event->setBaseDamage(0);
                    $event->setCancelled(true);
                }
            }
        }
    }

    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        if ($this->config["Guild Names in Chat"] && isset($this->config[$player->getName()])) {
            $guild = $this->config[$player->getName()]["Guild"]["Name"];
            $tag = $this->config["Guilds"][$guild]["Tag"];
            if ($this->config["Chat"]) {
                $event->setMessage("§F[§2" . $tag . "§f] " . $event->getMessage());
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $event->setJoinMessage(null);
        $newkills = 0;
        if ($this->config["Kills"][$player->getName()] == null) {
            $this->config["Kills"][$player->getName()] = $newkills;
        }
        if ($this->config[$player->getName()]) {
            $guild = $this->config[$player->getName()]["Guild"]["Name"];
            foreach ($this->config["Guilds"][$guild]["Players"] as $key => $value) {
                if (Server::getInstance()->getPlayer($key) != null) {
                    if (Server::getInstance()->getPlayer($key)->getName() == $player->getName()) {
                        if ($this->config["Guilds"][$guild]["Gmotd"] != null) {
                            $player->sendMessage($this->config["Guilds"][$guild]["Gmotd"]);
                        } else {
                            $player->sendMessage("You are a part of " . $guild);
                        }
                    } else {
                        $p = Server::getInstance()->getPlayer($key);
                        $p->sendMessage("§3" . $player->getName() . "§2 Has come online!");
                    }
                }
            }
        }
    }

    public function onKick(PlayerKickEvent $event)
    {
        $event->setQuitMessage(null);
    }

    public function teleport(Player $p, Player $p2)
    {
        $player = $p;
        $player2 = $p2;
        $loc = $player->getLocation();
        $loc2 = $player2->getLocation();
        $player->sendMessage("About to teleport, don't move!");
        $player2->sendMessage("About to teleport, don't move!");
        $this->getScheduler()->scheduleDelayedRepeatingTask(new TeleportTask($this, $player, $player2, $loc, $loc2), 20, 20);
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $event->setQuitMessage(null);
        if ($this->config[$player->getName()]) {
            $guild = $this->config[$player->getName()]["Guild"]["Name"];
            foreach ($this->config["Guilds"][$guild]["Players"] as $key => $value) {
                if (Server::getInstance()->getPlayer($key) != null) {
                    $p = Server::getInstance()->getPlayer($key);
                    $p->sendMessage("§3" . $player->getName() . "§2 Has gone offline!");
                }
            }
        }
    }

    public static function getInstance(): RpgGuilds
    {
        return self::$instance;
    }
}