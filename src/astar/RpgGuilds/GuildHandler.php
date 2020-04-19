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
        if ($args[0] === "생성") {
            if (count($args) != 3) {
                $player->sendMessage("§d[ §f길드 §d] §f잘못된 사용법 입니다, /길드 생성 <길드이름> <태그>을 사용하세요!");
                return true;
            }
            if (mb_strlen($args[2], 'utf-8') > 4) {
                $player->sendMessage("§d[ §f길드 §d] §f길드 태그는 4글자 이하여야 합니다!");
                return true;
            }
            if (isset($this->plugin->config["Guilds"])) {
                foreach ($this->plugin->config["Guilds"] as $guilds => $value) {
                    if ($args[2] === $this->plugin->config["Guilds"][$guilds]["Tag"]) {
                        $player->sendMessage("§d[ §f길드 §d] §f이 길드 태그는 이미 존재합니다!");
                        return true;
                    }
                }
            }
            if (!$player->hasPermission("guild.create")) {
                $player->sendMessage("§d[ §f길드 §d] §f이 명령을 사용할 권한이 없습니다!");
                return true;
            }
            if (isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f이미 가입된 길드가 있습니다! 새로운 길드를 만들 수 없습니다!");
                return true;
            }
            if (isset($this->plugin->config[str_replace("_", " ", $args[1])])) {
                $player->sendMessage("§d[ §f길드 §d] §f" . str_replace("_", " ", $args[1]) . " 이름을 가진 길드가 이미 존재합니다!");
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
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Leader"]["Title"] = "§c길드 마스터";
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
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["TP"] = false;
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["Ranks"]["Newbies"]["Title"] = "§b초보자";
            $this->plugin->config["Guilds"][str_replace("_", " ", $args[1])]["DefTerm"]["Default"] = "Newbies";
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            $player->sendMessage("§d[ §f길드 §d] §f축하합니다 " . $player->getName() . " 당신은 이제 새로 만들어진 길드 " . str_replace("_", " ", $args[1]) . "의 리더입니다.");
            return true;
        } else if ($args[0] === "초대") {
            if (count($args) != 2) {
                $player->sendMessage("§d[ §f길드 §d] §f잘못된 사용법 입니다, /길드 초대 <유저>을 사용하세요.");
                return true;
            }
            if (Server::getInstance()->getPlayer($args[1]) == null) {
                $player->sendMessage("§d[ §f길드 §d] §f해당 유저를 찾을 수 없습니다!");
                return true;
            }
            $p = Server::getInstance()->getPlayer($args[1]);
            $pi = $p->getName();
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["Invite"]) {
                $player->sendMessage("§d[ §f길드 §d] §f이 길드에 사람들을 초대 할 권한이 없습니다!");
                return true;
            }
            if (isset($this->plugin->config[$pi])) {
                $player->sendMessage("§d[ §f길드 §d] §f" . $pi . "님은 이미 길드에 있습니다!");
                return true;
            }
            if (isset($this->plugin->config["Pending"][$pi])) {
                $player->sendMessage("§d[ §f길드 §d] §f이 플레이어는 이미" . $this->plugin->config["Pending"][$pi]["Guild"] . "에서 대기중인 길드 초대를 받았습니다!");
                return true;
            }
            $this->plugin->config["Pending"][$pi]["Guild"] = $guildn;
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            $player->sendMessage("§d[ §f길드 §d] §f" . $pi . "님을 " . $guildn . "에 초대하였습니다!");
            $p->sendMessage("§d[ §f길드 §d] §f" . $guildn . "에서 대기중인 길드 초대가 있습니다! 이 길드에 가입하려면 </길드 수락>를 입력하십시오! 또는 </길드 거절>를 입력하여 거절하십시오!");
            return true;
        } else if ($args[0] === "수락") {
            if (!isset($this->plugin->config["Pending"][$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f대기중인 길드 초대가 없습니다!");
                return true;
            }
            $guildn2 = $this->plugin->config["Pending"][$player->getName()]["Guild"];
            if (!isset($this->plugin->config["Guilds"][$guildn2])) {
                $player->sendMessage("§d[ §f길드 §d] §f이 길드가 더 이상 존재하지 않습니다!");
                unset($this->plugin->config["Pending"][$player->getName()]);
                return true;
            }
            $newbies = $this->plugin->config["Guilds"][$guildn2]["DefTerm"]["Default"];
            $this->plugin->config["Guilds"][$guildn2]["Players"][$player->getName()]["Rank"] = $newbies;
            $this->plugin->config[$player->getName()]["Guild"]["Name"] = $guildn2;
            unset($this->plugin->config["Pending"][$player->getName()]);
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            foreach ($this->plugin->config["Guilds"][$guildn2]["Players"] as $key => $value) {
                if (Server::getInstance()->getPlayer($key) != null) {
                    if (Server::getInstance()->getPlayer($key)->getName() == $player->getName()) {
                        $player->sendMessage("§d[ §f길드 §d] §f". $guildn2 . "에 가입하였습니다!!!");
                    } else {
                        $p2 = Server::getInstance()->getPlayer($key);
                        $p2->sendMessage("§d[ §f길드 §d] §f" . $player->getName() . "님이 길드에 가입하였습니다!");
                    }
                }
            }
            return true;
        } else if ($args[0] === "거절") {
            if (!isset($this->plugin->config["Pending"][$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f대기중인 길드 초대가 없습니다!");
                return true;
            }
            $guildn2 = $this->plugin->config["Pending"][$player->getName()]["Guild"];
            $lead = $this->plugin->config["Guilds"][$guildn2]["Leader"];
            if (Server::getInstance()->getPlayer($lead) != null) {
                $gleader = Server::getInstance()->getPlayer($lead);
                $gleader->sendMessage("§d[ §f길드 §d] §f" . $player->getName() . "님이 길드 초대를 거절했습니다!");
            }
            $player->sendMessage("§d[ §f길드 §d] §f" . $guildn2 . " 가입을 거절했습니다!");
            unset($this->plugin->config["Pending"][$player->getName()]);
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            return true;
        } else if ($args[0] === "입장메시지") {
            $buffer = "";
            for ($i = 1; $i < count($args); ++$i) {
                $buffer = $buffer . " " . $args[$i];
            }
            $s = (string)$buffer;
            $s = str_replace("&", "§", $s);
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank = $this->plugin->config["Guilds"][$guildn]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn]["Ranks"][$grank]["Gmotd"]) {
                $player->sendMessage("§d[ §f길드 §d] §f입장메시지를 설정할 권한이 없습니다!");
                return true;
            }
            $this->plugin->config["Guilds"][$guildn]["Gmotd"] = $s;
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            $player->sendMessage("§d[ §f길드 §d] §f입장메시지를 다음과 같이 저장했습니다! " . $s);
            return true;
        } else if ($args[0] === "추방") {
            if (count($args) != 2) {
                $player->sendMessage("§d[ §f길드 §d] §f추방할 유저의 이름을 포함해주세요!");
                return true;
            }
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank2 = $this->plugin->config["Guilds"][$guildn2]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn2]["Ranks"][$grank2]["Kick"]) {
                $player->sendMessage("§d[ §f길드 §d] §f길드에서 유저를 추방 할 권한이 없습니다!");
                return true;
            }
            if (!isset($this->plugin->config["Guilds"][$guildn2]["Players"][$args[1]])) {
                $player->sendMessage("§d[ §f길드 §d] §f해당 유저는 길드원이 아닙니다!");
                return true;
            }
            $prank = $this->plugin->config["Guilds"][$guildn2]["Players"][$args[1]]["Rank"];
            if ($prank === "Leader") {
                $player->sendMessage("§d[ §f길드 §d] §f길드 리더를 추방할 수 없습니다!");
                return true;
            }

            unset($this->plugin->config["Guilds"][$guildn2]["Players"][$args[1]]);
            unset($this->plugin->config[$args[1]]);
            if (Server::getInstance()->getPlayer($args[1]) != null) {
                $p3 = Server::getInstance()->getPlayer($args[1]);
                $p3->sendMessage("§d[ §f길드 §d] §f" . $guildn2 . "에서 추방되었습니다!");
                $p3->setDisplayName($p3->getName());
            }
            $player->sendMessage("§d[ §f길드 §d] §f" . $guildn2 . "에서 " . $args[1] . "님을 추방하였습니다!");
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            return true;
        } else if ($args[0] === "해산") {
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            $grank2 = $this->plugin->config["Guilds"][$guildn2]["Players"][$player->getName()]["Rank"];
            if (!$this->plugin->config["Guilds"][$guildn2]["Ranks"][$grank2]["Disband"]) {
                $player->sendMessage("§d[ §f길드 §d] §f길드를 해산 할 권한이 없습니다!");
                return true;
            }
            foreach ($this->plugin->config["Guilds"][$guildn2]["Players"] as $key => $value) {
                unset($this->plugin->config[$key]);
                if (Server::getInstance()->getPlayer($key) != null) {
                    if (Server::getInstance()->getPlayer($key)->getName() == $player->getName()) {
                        $player->sendMessage("§d[ §f길드 §d] §f" . $guildn2 . "를 해산하였습니다!!!");
                        $player->setDisplayName($player->getName());
                    } else {
                        $p2 = Server::getInstance()->getPlayer($key);
                        $p2->setDisplayName($p2->getName());
                        $p2->sendMessage("§d[ §f길드 §d] §f" . $player->getName() . "님이 길드를 해산하였습니다!");
                    }
                }
            }
            unset($this->plugin->config["Guilds"][$guildn2]);
            $this->plugin->getConfig()->setAll($this->plugin->config);
            $this->plugin->saveConfig();
            return true;
        } else if ($args[0] === "탈퇴") {
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            foreach ($this->plugin->config["Guilds"][$guildn2]["Players"] as $key2 => $value2) {
                if (Server::getInstance()->getPlayer($key2)->getName() == $player->getName()) {
                    $player->sendMessage("§d[ §f길드 §d] §f" . $guildn2 . "를 탈퇴하였습니다!!!");
                } else {
                    if (Server::getInstance()->getPlayer($key2) == null) {
                        continue;
                    }
                    $p3 = Server::getInstance()->getPlayer($key2);
                    $p3->sendMessage("§d[ §f길드 §d] §f" . $player->getName() . "님이 길드를 탈퇴하였습니다!");
                }
            }
            unset($this->plugin->config["Guilds"][$guildn2]["Players"][$player->getName()]);
            unset($this->plugin->config[$player->getName()]);
            $player->setDisplayName($player->getName());
            return true;
        } else {
            if ($args[0] !== "목록") {
                if ($args[0] === "TP") {
                    if (!$player->isOp()) {
                        $player->sendMessage("§d[ §f길드 §d] §fOP만 길드 순간이동 작동을 변경할 수 있습니다!");
                        return true;
                    }
                    if (count($args) != 2) {
                        $player->sendMessage("§d[ §f길드 §d] §f잘못된 사용법 입니다! /길드 tp on 또는 /길드 tp off을 사용하세요!");
                        return true;
                    }
                    if ($args[1] === "on") {
                        $this->plugin->config["TP"] = true;
                        $player->sendMessage("§d[ §f길드 §d] §f길드원 순간이동이 서버에서 허용됩니다!");
                        return true;
                    }
                    if ($args[1] === "off") {
                        $this->plugin->config["TP"] = false;
                        $player->sendMessage("§d[ §f길드 §d] §f길드원 순간이동은 더 이상 서버에서 허용되지 않습니다!");
                        return true;
                    }
                }
                return false;
            }
            if (count($args) != 1) {
                $player->sendMessage("§d[ §f길드 §d] §f잘못된 사용법 입니다! /길드 목록을 사용하세요!");
                return true;
            }
            if (!isset($this->plugin->config[$player->getName()])) {
                $player->sendMessage("§d[ §f길드 §d] §f가입된 길드가 존재하지 않습니다!");
                return true;
            }
            $guildn2 = $this->plugin->config[$player->getName()]["Guild"]["Name"];
            foreach ($this->plugin->config["Guilds"][$guildn2]["Players"] as $key2 => $value2) {
                if (Server::getInstance()->getPlayer($key2) != null) {
                    $p3 = Server::getInstance()->getPlayer($key2);
                    $name = $p3->getName();
                    $rank = $this->plugin->config["Guilds"][$guildn2]["Players"][$name]["Rank"];
                    $title = $this->plugin->config["Guilds"][$guildn2]["Ranks"][$rank]["Title"];
                    $player->sendMessage("§d[ " . $title . " §d]§f" . " " . $name . " - 상태 §a접속중\n");
                } else {
                    $rank2 = $this->plugin->config["Guilds"][$guildn2]["Players"][$key2]["Rank"];
                    $title2 = $this->plugin->config["Guilds"][$guildn2]["Ranks"][$rank2]["Title"];
                    $player->sendMessage("§d[ " . $title2 . " §d]§f" . " " . $key2 . " - 상태 §7미접속중\n");
                }
            }
            $player->sendMessage("§d[ §f길드 §d] §f목록이 완성되었습니다!");
            return true;
        }
    }
}