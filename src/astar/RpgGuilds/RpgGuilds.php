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
        $this->getCommand("길드")->setExecutor(new GuildHandler($this));
        $this->getCommand("g")->setExecutor(new gcHandler($this));
        $this->getCommand("o")->setExecutor(new oHandler($this));
        $this->getCommand("gtp")->setExecutor(new gTP($this));
        $this->getCommand("길드정보")->setExecutor(new guildslist($this));
        $this->getCommand("조회")->setExecutor(new lookUpCommand($this));
        $this->getCommand("길드본부")->setExecutor(new hqCommand($this));
        $this->getCommand("길드계급")->setExecutor(new gRankHandler($this));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("§d[ §f길드 §d] §fRpgGuilds가 활성화 되었습니다!");

        $this->saveDefaultConfig();
        $this->config = $this->getConfig()->getAll();
        if (!isset($this->config["TP"])) {
            $this->config["TP"] = false;
            $this->getConfig()->setAll($this->config);
            $this->saveConfig();
        }
        if (!isset($this->config["Guild Names in Chat"])) {
            $this->config["Guild Names in Chat"] = true;
            $this->getConfig()->setAll($this->config);
            $this->saveConfig();
        }
        if (!isset($this->config["Chat"])) {
            $this->config["Chat"] = true;
            $this->getConfig()->setAll($this->config);
            $this->saveConfig();
        }
        if (!isset($this->config["No Build"])) {
            $this->config["No Build"] = true;
            $this->getConfig()->setAll($this->config);
            $this->saveConfig();
        }
        $this->config["Guilds"] = [];
    }

    public function onDisable()
    {
        $this->getLogger()->info("§d[ §f길드 §d] §fRpgGuilds가 비활성화 되었습니다!");
        $this->getConfig()->setAll($this->config);
        $this->saveConfig();
    }

    public function guildBreak(BlockBreakEvent $event)
    {
        $p = $event->getPlayer();
        if ($this->config["No Build"]) {
            $loc = $p->getLocation();
            foreach ($this->config["Guilds"] as $guild => $value) {
                if (isset($this->config["Guilds"][$guild]["HQ"])) {
                    $X1 = $this->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->config["Guilds"][$guild]["HQ"]["World"];
                    if ($p->getLevel()->getName() !== $world1) {
                        continue;
                    }
                    $gloc = new Location($X1, $Y1, $Z1, 0, 0, Server::getInstance()->getLevelByName($world1));
                    if ($loc->distance($gloc) > 50.0) {
                        continue;
                    }
                    if (!isset($this->config[$p->getName()])) {
                        $p->sendMessage("§d[ §f길드 §d] §f" . $guild . "의 길드본부에서 건설 할 수 없습니다!");
                        $event->setCancelled(true);
                        return;
                    }
                    if ($this->config[$p->getName()]["Guild"]["Name"] !== $guild) {
                        $p->sendMessage("§d[ §f길드 §d] §f" . $guild . "의 길드본부에서 건설 할 수 없습니다!");
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
                if (isset($this->config["Guilds"][$guild]["HQ"])) {
                    $X1 = $this->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->config["Guilds"][$guild]["HQ"]["World"];
                    if ($p->getLevel()->getName() !== $world1) {
                        continue;
                    }
                    $gloc = new Location($X1, $Y1, $Z1, 0, 0, Server::getInstance()->getLevelByName($world1));
                    if ($loc->distance($gloc) > 50.0) {
                        continue;
                    }
                    if (!isset($this->config[$p->getName()])) {
                        $p->sendMessage("§d[ §f길드 §d] §f" . $guild . "의 길드본부에서 건설 할 수 없습니다!");
                        $event->setCancelled(true);
                        return;
                    }
                    if ($this->config[$p->getName()]["Guild"]["Name"] !== $guild) {
                        $p->sendMessage("§d[ §f길드 §d] §f" . $guild . "의 길드본부에서 건설 할 수 없습니다!");
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
                if (isset($this->config["Guilds"][$guild]["HQ"])) {
                    $X1 = $this->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->config["Guilds"][$guild]["HQ"]["World"];
                    if ($p->getLevel()->getName() !== $world1) {
                        continue;
                    }
                    $gloc = new Location($X1, $Y1, $Z1, 0, 0, Server::getInstance()->getLevelByName($world1));
                    if ($loc->distance($gloc) > 50.0) {
                        continue;
                    }
                    if (!isset($this->config[$p->getName()])) {
                        $p->sendMessage("§d[ §f길드 §d] §f" . $guild . "의 길드본부에서 건설 할 수 없습니다!");
                        $event->setCancelled(true);
                        return;
                    }
                    if ($this->config[$p->getName()]["Guild"]["Name"] !== $guild) {
                        $p->sendMessage("§d[ §f길드 §d] §f" . $guild . "의 길드본부에서 건설 할 수 없습니다!");
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
                if (isset($this->config["Guilds"][$guild]["HQ"])) {
                    $X1 = $this->config["Guilds"][$guild]["HQ"]["X"];
                    $Y1 = $this->config["Guilds"][$guild]["HQ"]["Y"];
                    $Z1 = $this->config["Guilds"][$guild]["HQ"]["Z"];
                    $world1 = $this->config["Guilds"][$guild]["HQ"]["World"];
                    if ($p->getLevel()->getName() !== $world1) {
                        continue;
                    }
                    $gloc = new Location($X1, $Y1, $Z1, 0, 0, Server::getInstance()->getLevelByName($world1));
                    if ($loc->distance($gloc) > 50.0) {
                        continue;
                    }
                    if (!isset($this->config[$p->getName()])) {
                        $p->sendMessage("§d[ §f길드 §d] §f" . $guild . "의 길드본부에서 건설 할 수 없습니다!");
                        $event->setCancelled(true);
                        return;
                    }
                    if ($this->config[$p->getName()]["Guild"]["Name"] !== $guild) {
                        $p->sendMessage("§d[ §f길드 §d] §f" . $guild . "의 길드본부에서 건설 할 수 없습니다!");
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
        $pl = null;
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
        $child = null;
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
                $event->setMessage("§d[§b " . $tag . " §d] §f" . $event->getMessage());
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $event->setJoinMessage(null);
        $newkills = 0;
        if (!isset($this->config["Kills"][$player->getName()])) {
            $this->config["Kills"][$player->getName()] = $newkills;
        }
        if (isset($this->config[$player->getName()])) {
            $guild = $this->config[$player->getName()]["Guild"]["Name"];
            foreach ($this->config["Guilds"][$guild]["Players"] as $key => $value) {
                if (Server::getInstance()->getPlayer($key) != null) {
                    if (Server::getInstance()->getPlayer($key)->getName() == $player->getName()) {
                        if (isset($this->config["Guilds"][$guild]["Gmotd"])) {
                            $player->sendMessage("§d[ §f길드 §d] §f" . $this->config["Guilds"][$guild]["Gmotd"]);
                        } else {
                            $player->sendMessage("§d[ §f길드 §d] §f당신은 " . $guild . "의 구성원입니다!");
                        }
                    } else {
                        $p = Server::getInstance()->getPlayer($key);
                        $p->sendMessage("§d[ §f길드 §d] §f" . $player->getName() . "님이 접속하였습니다!");
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
        $player->sendMessage("§d[ §f길드 §d] §f곧 순간이동 됩니다, 움직이지 마세요!");
        $player2->sendMessage("§d[ §f길드 §d] §f곧 순간이동 됩니다, 움직이지 마세요!");
        $this->getScheduler()->scheduleDelayedRepeatingTask(new TeleportTask($this, $player, $player2, $loc, $loc2), 20, 20);
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $event->setQuitMessage(null);
        if (isset($this->config[$player->getName()])) {
            $guild = $this->config[$player->getName()]["Guild"]["Name"];
            foreach ($this->config["Guilds"][$guild]["Players"] as $key => $value) {
                if (Server::getInstance()->getPlayer($key) != null) {
                    $p = Server::getInstance()->getPlayer($key);
                    $p->sendMessage("§d[ §f길드 §d] §f" . $player->getName() . "님이 접속을 종료하였습니다!");
                }
            }
        }
    }

    public static function getInstance(): RpgGuilds
    {
        return self::$instance;
    }
}