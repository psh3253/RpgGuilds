<?php

namespace astar\RpgGuilds;

use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class gRankHandler implements CommandExecutor
{
    /**
     * @var gRankHandler
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
        if ($args[0] === "create") {
            $player = $sender;
            if (count($args) != 3) {
                $player->sendMessage("Improper usage! Please use /grank create rank title");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? how can you expect to create a rank one?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["CreateRank"]) {
                $player->sendMessage("You do not have permission to create a rank in this guild!");
                return true;
            }
            if ($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]) {
                $player->sendMessage("This rank already exists!");
                return true;
            }
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["Title"] = str_replace("&", "§", str_replace("_", " ", $args[2]));
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["invite"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["kick"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["Gmotd"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["Disband"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["Gchat"] = true;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["RankSet"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["RankTitle"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["CreateRank"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["DeleteRank"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["PlayerInfo"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["RankPerms"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["Ochat"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["PlayerNotes"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["PlayerNotesView"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["PlayerNotesSet"] = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["TP"] = false;
            $this->plugin->saveConfig();
            $player->sendMessage("You have successfully created the rank " . str_replace("_", " ", $args[1]) . "!");
            return true;
        } else if ($args[0] === "delete") {
            $player = $sender;
            if (count($args) <= 1) {
                $player->sendMessage("You must include a rank to delete!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? how can you expect to delete a rank?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["DeleteRank"]) {
                $player->sendMessage("You do not have permission to delete a rank in this guild!");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]) {
                $player->sendMessage("This rank doesn't exist!");
                return true;
            }

            $newbies = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Default"];
            foreach ($this->plugin->config["Guilds"][$guildn]["Players"] as $key => $value) {
                $rank = $this->plugin->config["Guilds"][$guildn]["Players"][$key]["Rank"];
                if (str_replace("_", " ", $args[1]) === $rank) {
                    $this->plugin->config["Guilds"][$guildn]["Players"][$key]["Rank"] = $newbies;
                    if (Server::getInstance()->getPlayer($key) == null) {
                        continue;
                    }

                    $p = Server::getInstance()->getPlayer($key);
                    $p->sendMessage("§3 Your rank has been changed to " . $newbies . "!");
                }
            }
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])] = null;
            $this->plugin->saveConfig();
            $player->sendMessage("You have successfully deleted the rank " . str_replace("_", " ", $args[1]) . "!");
            return true;
        } else if ($args[0] === "set") {
            $player = $sender;
            if (count($args) != 3) {
                $player->sendMessage("Improper usage! Please use /grank set playername rank");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            $prank = $this->plugin->config["Guilds"][$guildn]["Players"][$args[1]]["Rank"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? how can you expect to delete a rank?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["RankSet"]) {
                $player->sendMessage("You do not have permission to set a players rank in this guild!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[2])])) {
                $player->sendMessage("This rank doesn't exist!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Players"][$args[1]])) {
                $player->sendMessage("That player is not a part of your guild!");
                return true;
            }
            $leader = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Leader"];
            $player->sendMessage("grank = " . $grank . "prank = " . $prank . "guiln = " . $guildn . " leader = " . $leader);

            $newbie = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Default"];
            if (str_replace("_", " ", $args[2]) === $leader && !$grank === $leader) {
                $player->sendMessage("You can not promote someone to Guild Leader this way!!");
                return true;
            }
            if (str_replace("_", " ", $args[2]) === $leader && $grank === $leader) {
                $this->plugin->config["Guilds"][$guildn]["Players"][$args[1]]["Rank"] = $leader;
                $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"] = $newbie;
                $this->plugin->config["Guilds"][$guildn]["Leader"] = $args[1];
                $this->plugin->saveConfig();
                $player->sendMessage("You are no longer the leader of " . $guildn . ".");
                foreach ($this->plugin->config["Guilds"][$guildn]["Players"] as $key2 => $value2) {
                    if (Server::getInstance()->getPlayer($key2) != null) {
                        $p2 = Server::getInstance()->getPlayer($key2);
                        if (!$p2->getName() === $args[1]) {
                            $p2->sendMessage($args[1] . " is the new Leader of " . $guildn . ".");
                        } else {
                            $p2->sendMessage("You are the new Guild leader of " . $guildn . "!");
                        }
                    }
                }
                return true;
            }
            if ($prank === $grank) {
                $player->sendMessage("You can not change someone's rank if their rank is the same as yours!");
                return true;
            }
            if (str_replace("_", " ", $args[2]) === $grank) {
                $player->sendMessage("You can not set someone to the same rank as yours!");
                return true;
            }
            if ($prank === $leader) {
                $player->sendMessage("You cannot change a guild leaders rank in this fashion!");
                return true;
            }
            foreach ($this->plugin->config["Guilds"][$guildn]["Players"] as $key2 => $value2) {
                if (Server::getInstance()->getPlayer($key2) != null) {
                    $p2 = Server::getInstance()->getPlayer($key2);
                    if (!$p2->getName() === $args[1]) {
                        $p2->sendMessage($args[1] . " has been changed to the rank " . str_replace("_", " ", $args[2]) . ".");
                    } else {
                        $p2->sendMessage("You have been moved to the rank " . str_replace("_", " ", $args[2]) . ".");
                    }
                }
            }
            $this->plugin->config["Guilds"][$guildn]["Players"][$args[1]]["Rank"] = str_replace("_", " ", $args[2]);
            $this->plugin->saveConfig();
            return true;
        } else if ($args[0] === "title") {
            $player = $sender;
            if (count($args) != 3) {
                $player->sendMessage("Improper usage! Please use /grank title rankname newtitle");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? how can you expect to delete a rank?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["RankTitle"]) {
                $player->sendMessage("You do not have permission to set a rank's Title in this guild!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])])) {
                $player->sendMessage("This rank doesn't exist!");
                return true;
            }
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["Title"] = str_replace("&", "§", str_replace("_", " ", $args[2]));
            $this->plugin->saveConfig();
            $player->sendMessage("You have changed " . str_replace("_", " ", $args[1]) . "'s title to " . str_replace("&", "§", str_replace("_", " ", $args[2])) . ".");
            return true;
        } else if ($args[0] === "perms") {
            $player = $sender;
            if (count($args) != 4) {
                $player->sendMessage("Improper usage! Please use /grank perms rankname permission_name true/false");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("You are not even in a guild? how can you expect to delete a rank?");
                return true;
            }
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["RankPerms"]) {
                $player->sendMessage("You do not have permission to set a rank's permissions in this guild!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])])) {
                $player->sendMessage("This rank doesn't exist!");
                return true;
            }
            $leader2 = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Leader"];
            if (str_replace("_", " ", $args[1]) === $leader2) {
                $player->sendMessage("You can not change a guild leaders permissions!");
                return true;
            }
            if ($args[3] === "true") {
                $permbool = true;
                $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])][str_replace("_", " ", $args[2])] = $permbool;
                $this->plugin->saveConfig();
                $player->sendMessage("Members with the rank " . str_replace("_", " ", $args[1]) . " now have  " . str_replace("_", " ", $args[2]) . " permissions.");
                return true;
            }
            $permbool = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])][str_replace("_", " ", $args[2])] = $permbool;
            $this->plugin->saveConfig();
            $player->sendMessage("Members with the rank " . str_replace("_", " ", $args[1]) . " no longer have  " . str_replace("_", " ", $args[2]) . " permissions.");
            return true;
        }
        else
        {
            if ($args[0] === "list") {
                $player = $sender;
                $player->sendMessage("The available rank permissions are \nInvite\nKick\nGmotd\nDisband\nGchat\nRankSet\nRankTitle\nCreateRank\nDeleteRank\nPlayerInfo\nRankPerms\nOchat\nPlayerNotesView\nPlayerNotesSet\nTitle");
                return true;
            }
            if ($args[0] === "defaults") {
                $player = $sender;
                $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
                $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
                $leader2 = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Leader"];
                if (!$grank === $leader2) {
                    $player->sendMessage("Only a guild leader can change the default rank names!");
                    return true;
                }
                if ($args[1] === "leader") {
                    $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Leader"] = str_replace("_", " ", str_replace("&", "§", $args[2]));
                    $this->plugin->saveConfig();
                    $player->sendMessage("You have changed the leader rank name to " . str_replace("_", " ", str_replace("&", "§", $args[2])) . ".");
                    return true;
                }
                if ($args[1] === "newbies") {
                    $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Default"] = str_replace("_", " ", str_replace("&", "§", $args[2]));
                    $this->plugin->saveConfig();
                    $player->sendMessage("You have changed the default rank name to " . str_replace("_", " ", str_replace("&", "§", $args[2])) . ".");
                    return true;
                }
                $player->sendMessage("Improper usage! Please use /grank defaults {leader/newbies} new_rank_name");
                return true;
            }
            else{
                return true;
            }
        }
    }
}