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
        if ($args[0] === "생성") {
            $player = $sender;
            if (count($args) != 3) {
                $player->sendMessage("§d[ §f길드 §d] §f잘못된 사용법입니다! /길드계급 생성 <계급> <칭호>를 사용하세요!");
                return true;
            }
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["CreateRank"]) {
                $player->sendMessage("§d[ §f길드 §d] §f이 길드에서 계급을 생성할 권한이 없습니다!");
                return true;
            }
            if (isset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])])) {
                $player->sendMessage("§d[ §f길드 §d] §f이 계급은 이미 존재합니다!");
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
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["TP"] = false;
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            $player->sendMessage("§d[ §f길드 §d] §f성공적으로 계급 ".str_replace("_", " ", $args[1])."를 생성하였습니다!");
            return true;
        } else if ($args[0] === "삭제") {
            $player = $sender;
            if (count($args) <= 1) {
                $player->sendMessage("§d[ §f길드 §d] §f삭제할 계급을 포함해야합니다!");
                return true;
            }
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["DeleteRank"]) {
                $player->sendMessage("§d[ §f길드 §d] §f이 길드에서 계급을 삭제할 권한이 없습니다!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])])) {
                $player->sendMessage("§d[ §f길드 §d] §f이 계급은 존재하지 않습니다!");
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
                    $p->sendMessage("§d[ §f길드 §d] §f계급이 " . $newbies . "로 변경되었습니다!");
                }
            }
            unset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]);
            $this->plugin->saveConfig();
            $player->sendMessage("§d[ §f길드 §d] §f성공적으로 계급 " . str_replace("_", " ", $args[1]) . "를 삭제하였습니다!");
            return true;
        } else if ($args[0] === "설정") {
            $player = $sender;
            if (count($args) != 3) {
                $player->sendMessage("§d[ §f길드 §d] §f잘못된 사용법입니다! /길드계급 설정 <유저> <계급>을 사용하세요!");
                return true;
            }
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["RankSet"]) {
                $player->sendMessage("§d[ §f길드 §d] §f이 길드에서 플레이어 계급을 설정할 권한이 없습니다!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[2])])) {
                $player->sendMessage("§d[ §f길드 §d] §f이 계급은 존재하지 않습니다!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Players"][$args[1]])) {
                $player->sendMessage("§d[ §f길드 §d] §f해당 유저는 길드의 구성원이 아닙니다!");
                return true;
            }
            $leader = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["길드마스터"];
            //$player->sendMessage("grank = " . $grank . "prank = " . $prank . "guiln = " . $guildn . " leader = " . $leader);

            $newbie = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Default"];
            if (str_replace("_", " ", $args[2]) === $leader && !$grank === $leader) {
                $player->sendMessage("§d[ §f길드 §d] §f이런식으로 해당 유저를 길드 리더로 승격시킬 수 없습니다!!");
                return true;
            }
            if (str_replace("_", " ", $args[2]) === $leader && $grank === $leader) {
                $this->plugin->config["Guilds"][$guildn]["Players"][$args[1]]["Rank"] = $leader;
                $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"] = $newbie;
                $this->plugin->config["Guilds"][$guildn]["길드마스터"] = $args[1];
                $this->plugin->getConfig()->setAll($this->plugin->config);
                $this->plugin->saveConfig();
                $player->sendMessage("§d[ §f길드 §d] §f당신은 더 이상 " . $guildn . "의 리더가 아닙니다!");
                foreach ($this->plugin->config["Guilds"][$guildn]["Players"] as $key2 => $value2) {
                    if (Server::getInstance()->getPlayer($key2) != null) {
                        $p2 = Server::getInstance()->getPlayer($key2);
                        if ($p2->getName() !== $args[1]) {
                            $p2->sendMessage("§d[ §f길드 §d] §f" . $args[1] . "님은 " . $guildn . "의 새로운 리더입니다!");
                        } else {
                            $p2->sendMessage("§d[ §f길드 §d] §f당신은 " . $guildn . "의 새로운 길드 리더 입니다!");
                        }
                    }
                }
                return true;
            }
            $prank = $this->plugin->config["Guilds"][$guildn]["Players"][$args[1]]["Rank"];
            if ($prank === $grank) {
                $player->sendMessage("§d[ §f길드 §d] §f해당 유저의 계급과 당신의 계급이 같으면 해당 유저의 계급을 변경할 수 없습니다!");
                return true;
            }
            if (str_replace("_", " ", $args[2]) === $grank) {
                $player->sendMessage("§d[ §f길드 §d] §f해당 유저를 당신의 계급과 같게 설정할 수 없습니다!");
                return true;
            }
            if ($prank === $leader) {
                $player->sendMessage("§d[ §f길드 §d] §f이런식으로 길드 리더의 계급을 변경할 수 없습니다!");
                return true;
            }
            foreach ($this->plugin->config["Guilds"][$guildn]["Players"] as $key2 => $value2) {
                if (Server::getInstance()->getPlayer($key2) != null) {
                    $p2 = Server::getInstance()->getPlayer($key2);
                    if ($p2->getName() !== $args[1]) {
                        $p2->sendMessage("§d[ §f길드 §d] §f" . $args[1] . "님이 " . str_replace("_", " ", $args[2]) . " 계급으로 변경되었습니다!");
                    } else {
                        $p2->sendMessage("§d[ §f길드 §d] §f당신은 " . str_replace("_", " ", $args[2]) . " 계급으로 변경되었습니다!");
                    }
                }
            }
            $this->plugin->config["Guilds"][$guildn]["Players"][$args[1]]["Rank"] = str_replace("_", " ", $args[2]);
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            return true;
        } else if ($args[0] === "칭호") {
            $player = $sender;
            if (count($args) != 3) {
                $player->sendMessage("§d[ §f길드 §d] §f잘못된 사용법입니다! /길드계급 칭호 <계급> <칭호>을 사용하세요!");
                return true;
            }
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["RankTitle"]) {
                $player->sendMessage("§d[ §f길드 §d] §f이 길드에서 계급의 칭호를 설정할 권한이 없습니다!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])])) {
                $player->sendMessage("§d[ §f길드 §d] §f이 계급은 존재하지 않습니다!");
                return true;
            }
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])]["Title"] = str_replace("&", "§", str_replace("_", " ", $args[2]));
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            $player->sendMessage(str_replace("_", " ", $args[1]) . "의 칭호를 " . str_replace("&", "§", str_replace("_", " ", $args[2])) . "로 변경하였습니다!");
            return true;
        } else if ($args[0] === "권한") {
            $player = $sender;
            if (count($args) != 4) {
                $player->sendMessage("§d[ §f길드 §d] §f잘못된 사용법 입니다! /길드계급 권한 <계급> <권한> <true/false>을 사용하세요!");
                return true;
            }
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["RankPerms"]) {
                $player->sendMessage("§d[ §f길드 §d] §f이 길드에서 계급의 권한을 설정할 권한이 없습니다!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])])) {
                $player->sendMessage("§d[ §f길드 §d] §f이 계급은 존재하지 않습니다!");
                return true;
            }
            $leader2 = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["길드마스터"];
            if (str_replace("_", " ", $args[1]) === $leader2) {
                $player->sendMessage("§d[ §f길드 §d] §f길드 리더의 권한을 변경할 수 없습니다!");
                return true;
            }
            if ($args[3] === "true") {
                $permbool = true;
                $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])][str_replace("_", " ", $args[2])] = $permbool;
                $this->plugin->getConfig()->setAll($this->plugin->config);
                $this->plugin->saveConfig();
                $player->sendMessage("§d[ §f길드 §d] §f" . str_replace("_", " ", $args[1]) . " 계급인 유저들은 이제 " . str_replace("_", " ", $args[2]) . " 권한을 갖습니다!");
                return true;
            }
            $permbool = false;
            $this->plugin->config["Guilds"][$guildn]["Ranks"][str_replace("_", " ", $args[1])][str_replace("_", " ", $args[2])] = $permbool;
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            $player->sendMessage("§d[ §f길드 §d] §f" . str_replace("_", " ", $args[1]) . " 계급인 유저들은 더 이상 " . str_replace("_", " ", $args[2]) . " 권한을 갖지 않습니다!");
            return true;
        } else {
            if ($args[0] === "목록") {
                $player = $sender;
                $player->sendMessage("§d[ §f길드 §d] §f이용 가능한 계급 권한 \nInvite\nKick\nGmotd\nDisband\nGchat\nRankSet\nRankTitle\nCreateRank\nDeleteRank\nRankPerms\nOchat");
                return true;
            }
            /**
            if ($args[0] === "기본값") {
                $player = $sender;
                $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
                $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
                $leader2 = $this->plugin->config["Guilds"][$guildn]["DefTerm"]["길드마스터"];
                if (!$grank === $leader2) {
                    $player->sendMessage("오직 길드 리더만 기본 권한 이름을 변경할 수 있습니다!");
                    return true;
                }
                if ($args[1] === "리더") {
                    $this->plugin->config["Guilds"][$guildn]["DefTerm"]["길드마스터"] = str_replace("_", " ", str_replace("&", "§", $args[2]));
                    $this->plugin->saveConfig();
                    $player->sendMessage("리더 계급 이름을 " . str_replace("_", " ", str_replace("&", "§", $args[2])) . "로 변경하였습니다.");
                    return true;
                }
                if ($args[1] === "초보자") {
                    $this->plugin->config["Guilds"][$guildn]["DefTerm"]["Default"] = str_replace("_", " ", str_replace("&", "§", $args[2]));
                    $this->plugin->saveConfig();
                    $player->sendMessage("기본 계급 이름을" . str_replace("_", " ", str_replace("&", "§", $args[2])) . "로 변경하였습니다.");
                    return true;
                }
                $player->sendMessage("잘못된 사용법 입니다! /길드계급 기본값 <리더/초보자> <계급 이름>");
                return true;
            } else {
                return true;
            }
             **/
            return true;
        }
    }
}