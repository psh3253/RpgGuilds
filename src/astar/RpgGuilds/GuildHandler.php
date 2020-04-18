<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class GuildHandler implements CommandExecutor
{
    /**
     * @var GuildHandler
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
        if (count($args) == 0) {
            return false;
        }
        if ($args[0] === "create") {
            if (count($args) != 3) {
                $player->sendMessage("Please use /guild create guildname tag");
                return true;
            }
            if (strlen($args[2]) != 4) {
                $player->sendMessage("Guild tags must be 4 letters!");
                return true;
            }
            if (isset($this->plugin->config["Guilds"])) {
                foreach ($this->plugin->config["Guilds"] as $guilds => $value) {
                    if ($args[2] === $this->plugin->config["Guilds"][$guilds]["Tag"]) {
                        $player->sendMessage("This guild tag already exists!");
                        return true;
                    }
                }
            }
            if (!$player->hasPermission("guild.create")) {
                $player->sendMessage("You do not have permission to use this command!");
                return true;
            }
            if (isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are already in a guild! you can't create a new one!");
                return true;
            }
            if (isset($this->plugin->config[str_replace("_", " ", $args[1])])) {
                $player->sendMessage("A guild named " . str_replace("_", " ", $args[1]) . " already exists!");
                return true;
            }
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Players"][$player->getName()]["Rank"] = "Leader";
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Leader"] = $player->getName();
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Tag"] = $args[2];
            $this->plugin->config[$player->getName()]["Guild"]["Name"] = str_replace("_", " ", $args[1]);
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["DefTerm"]["Leader"] = "Leader";
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["Invite"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["Ochat"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["Kick"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["Gmotd"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["Disband"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["Gchat"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["RankSet"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["RankTitle"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["CreateRank"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["DeleteRank"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["PlayerInfo"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["RankPerms"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["PlayerNotes"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["PlayerNotesView"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["PlayerNotesSet"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["TP"] = true;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["Title"] = "§4Guild Master";
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["Invite"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["Ochat"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["Kick"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["Gmotd"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["Disband"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["Gchat"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["RankSet"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["RankTitle"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["CreateRank"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["DeleteRank"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["PlayerInfo"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["RankPerms"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["PlayerNotes"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["PlayerNotesView"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["PlayerNotesSet"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["TP"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["Title"] = "§2Newbies";
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["DefTerm"]["Default"] = "Newbies";
            $this->plugin->saveConfig();
            $player->sendMessage("Congratulations " . $player->getName() . " you are now the leader of the newly created guild " . str_replace("_", " ", $args[1]));
            return true;
        } else if ($args[0] === "invite") {
            if (count($args) != 2) {
                $player->sendMessage("Improper usage of the guild invite command, please just use /guild invite playername");
                return true;
            }
            if (Server::getInstance()->getPlayer($args[1]) == null) {
                $player->sendMessage("This player cannot be found");
                return true;
            }
            $p = Server::getInstance()->getPlayer($args[1]);
            $pi = $p->getName();
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? how can you invite someone?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["Invite"]) {
                $player->sendMessage("You do not have permission to invite people to this guild!");
                return true;
            }
            if (isset($this->plugin->config[$pi])) {
                $player->sendMessage($pi . " is already in a guild!");
                return true;
            }
            if (isset($this->plugin->config["Pending"][$pi])) {
                $player->sendMessage("This player already has a pending guild invite from " . $this->plugin->config["Pending"][$pi]["Guild"]);
                return true;
            }
            $this->plugin->config["Pending"][$pi]["Guild"] = $guildn;
            $this->plugin->saveConfig();
            $player->sendMessage("You have invited " . $pi . " To join " . $guildn);
            $p->sendMessage("You have a pending guild invite from '" . $guildn . "' type </guild accept> to join this guild. or </guild deny> to turn it down.");
            return true;
        } else if ($args[0] === "accept") {
            if (!isset($this->plugin->config["Pending"][$player->getName()])) {
                $player->sendMessage("You do not have any pending guild invites!");
                return true;
            }
            $guildn2 = $this->plugin->config["Pending"][$player->getName()]["Guild"];
            if (!isset($this->plugin->config["Guilds"][$guildn2])) {
                $player->sendMessage("This guild no longer exists!");
                $this->plugin->config["Pending"][$player->getName()] = null;
                return true;
            }
            $newbies = $this->plugin->config["Guilds"][$guildn2]["DefTerm"]["Default"];
            $this->plugin->config["Guilds"][$guildn2]["Players"][$player->getName()]["Rank"] = $newbies;
            $this->plugin->config[$player->getName()]["Guild"]["Name"] = $guildn2;
            $this->plugin->config["Pending"][$player->getName()] = null;
            $this->plugin->saveConfig();
            if ($this->plugin->config["Chat"]) {
                $player->setDisplayName("§F[§2" . $guildn2 . "§f]" . $player->getDisplayName());
            }
            foreach ($this->plugin->config["Guilds"][$guildn2]["Players"] as $key => $value) {
                if (Server::getInstance()->getPlayer($key) != null) {
                    if (Server::getInstance()->getPlayer($key)->getName() == $player->getName()) {
                        $player->sendMessage("You Have Joined  " . $guildn2 . "!!!");
                    } else {
                        $p2 = Server::getInstance()->getPlayer($key);
                        $p2->sendMessage("§3" . $player->getName() . "§2 Has Joined The Guild!");
                    }
                }
            }
            return true;
        } else if ($args[0] === "deny") {
            if (!isset($this->plugin->config["Pending"][$player->getName()])) {
                $player->sendMessage("You do not have any pending guild invites!");
                return true;
            }
            $guildn2 = $this->plugin->config["Pending"][$player->getName()]["Guild"];
            $lead = $this->plugin->config["Guilds"][$guildn2]["Leader"];
            if (Server::getInstance()->getPlayer($lead) != null) {
                $gleader = Server::getInstance()->getPlayer($lead);
                $gleader->sendMessage($player->getName() . " has declined your guild invite.");
            }
            $player->sendMessage("You have declined joining " . $guildn2 . ".");
            $this->plugin->config["Pending"][$player->getName()] = null;
            $this->plugin->saveConfig();
            return true;
        } else if ($args[0] === "gmotd") {
            $buffer = "";
            for ($i = 1; $i < count($args); ++$i) {
                $buffer = $buffer . " " . $args[$i];
            }
            $s = (string)$buffer;
            $s = str_replace("&", "§", $s);
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? how can you set the Gmotd?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["Gmotd"]) {
                $player->sendMessage("You do not have permission to set the Gmotd!");
                return true;
            }
            $this->plugin->config["Guilds"][$guildn]["Gmotd"] = $s;
            $this->plugin->saveConfig();
            $player->sendMessage("You have saved the gmotd as §2" . $s);
            return true;
        } else if ($args[0] === "Kick") {
            $guildn2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank2 = $this->plugin->config["Guilds"][$guildn2]["Players"][$player->getName()]["Rank"];
            if (count($args) != 2) {
                $player->sendMessage("Please include the name of the person you want to kick.");
                return true;
            }
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? How can you kick someone?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn2]["Ranks"][$grank2]["Kick"]) {
                $player->sendMessage("You do not have permission to kick someone from the guild!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn2]["Players"][Server::getInstance()->getPlayer($args[1])->getName()])) {
                $player->sendMessage("This player is not a member of your guild!");
                return true;
            }
            $prank = $this->plugin->config["Guilds"][$guildn2]["Players"][$args[1]]["Rank"];
            if ($prank === "Leader") {
                $player->sendMessage("You cannot kick a guild leader!");
                return true;
            }
            $this->plugin->config["Guilds"][$guildn2]["Players"][$args[1]] = null;
            $this->plugin->config[$args[1]] = null;
            if (Server::getInstance()->getPlayer($args[1]) != null) {
                $p3 = Server::getInstance()->getPlayer($args[1]);
                $p3->sendMessage("You have been removed from " . $guildn2 . ".");
                $p3->setDisplayName($p3->getName());
            }
            $player->sendMessage("You have removed " . $args[1] . " from " . $guildn2 . ".");
            $this->plugin->saveConfig();
            return true;
        } else if ($args[0] === "disband") {
            $guildn2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank2 = $this->plugin->config["Guilds"][$guildn2]["Players"][$player->getName()]["Rank"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? How can you disband it?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn2]["Ranks"][$grank2]["Disband"]) {
                $player->sendMessage("You do not have permission to disband the guild!");
                return true;
            }
            foreach ($this->plugin->config["Guilds"][$guildn2]["Players"] as $key => $value) {
                $this->plugin->config[$key] = null;
                if (Server::getInstance()->getPlayer($key) != null) {
                    if (Server::getInstance()->getPlayer($key)->getName() == $player->getName()) {
                        $player->sendMessage("§4You Have Disbanded  " . $guildn2 . "!!!");
                        $player->setDisplayName($player->getName());
                    } else {
                        $p2 = Server::getInstance()->getPlayer($key);
                        $p2->setDisplayName($p2->getName());
                        $p2->sendMessage("§3" . $player->getName() . "§4 Has Disbanded The Guild!");
                    }
                }
            }
            $this->plugin->config["Guilds"][$guildn2] = null;
            $this->plugin->saveConfig();
            return true;
        } else if ($args[0] === "quit") {
            $guildn2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? How can you quit?");
                return true;
            }
            foreach ($this->plugin->config["Guilds"][$guildn2]["Players"] as $key2 => $value2) {
                if (Server::getInstance()->getPlayer($key2)->getName() == $player->getName()) {
                    $player->sendMessage("§4You Have Left  " . $guildn2 . "!!!");
                } else {
                    if (Server::getInstance()->getPlayer($key2) == null) {
                        continue;
                    }
                    $p3 = Server::getInstance()->getPlayer($key2);
                    $p3->sendMessage("§3" . $player->getName() . "§4 Has Quit The Guild!");
                }
            }
            $this->plugin->config["Guilds"][$guildn2]["Players"][$player->getName()] = null;
            $this->plugin->config[$player->getName()] = null;
            $player->setDisplayName($player->getName());
            return true;
        } else {
            if (!$args[0] === "List") {
                if ($args[0] === "TP") {
                    if (!$player->isOp()) {
                        $player->sendMessage("Only ops can change the default server teleport behavior!");
                        return true;
                    }
                    if (count($args) != 2) {
                        $player->sendMessage("wrong usage! please use /guild tp on  or guild tp off!");
                        return true;
                    }
                    if ($args[1] === "on") {
                        $this->plugin->config["TP"] = true;
                        $player->sendMessage("Teleporting guild members is now allowed on your server!");
                        return true;
                    }
                    if ($args[1] === "off") {
                        $this->plugin->config["TP"] = false;
                        $player->sendMessage("Teleporting guild members is no longer allowed on your server!");
                        return true;
                    }
                }
                return false;
            }
            if (count($args) != 1) {
                $player->sendMessage("Improper usage! Please use /guild list");
                return true;
            }
            $guildn2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? how can you expect to list online members?");
                return true;
            }
            foreach ($this->plugin->config["Guilds"][$guildn2]["Players"] as $key2 => $value2) {
                if (Server::getInstance()->getPlayer($key2) != null) {
                    $p3 = Server::getInstance()->getPlayer($key2);
                    $name = $p3->getName();
                    $rank = $this->plugin->config["Guilds"][$guildn2]["Players"][$name]["Rank"];
                    $title = $this->plugin->config["Guilds"][$guildn2]["Players"][$name]["Title"];
                    $player->sendMessage("§F[" . $title . "§F]" . " " . $name . " - Status §2Online\n");
                } else {
                    $rank2 = $this->plugin->config["Guilds"][$guildn2]["Players"][$key2]["Rank"];
                    $title2 = $this->plugin->config["Guilds"][$guildn2]["Players"][$rank2]["Title"];
                    $player->sendMessage("§F[" . $title2 . "§F]" . " " . $key2 . " - Status §8Offline\n");
                }
            }
            $player->sendMessage("List complete");
            return true;
        }
    }
}