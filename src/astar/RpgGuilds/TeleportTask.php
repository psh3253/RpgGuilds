<?php

namespace astar\RpgGuilds;

use pocketmine\level\Location;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class TeleportTask extends Task
{
    /**
     * @var RpgGuilds
     */
    private $owner;

    /**
     * @var Player
     */
    private $player;

    /**
     * @var Player
     */
    private $player2;

    /**
     * @var Location
     */
    private $loc;

    /**
     * @var Location
     */
    private $loc2;

    /**
     * @var int
     */
    private $count;

    public function __construct(RpgGuilds $owner, Player $player, Player $player2, Location $loc, Location $loc2)
    {
        $this->count = 8;
        $this->owner = $owner;
        $this->player = $player;
        $this->player2 = $player2;
        $this->loc = $loc;
        $this->loc2 = $loc2;
    }

    public function onRun(int $currentTick)
    {
        $this->player->sendMessage("Wait " . $this->count . " Seconds.");
        $this->player2->sendMessage("Wait " . $this->count . " Seconds.");
        --$this->count;
        if ($this->player->getLocation()->getX() != $this->loc->getX()) {
            $this->player->sendMessage("Cancelled teleport, don't move!");
            $this->player2->sendMessage("Cancelled teleport, don't move!");
            $this->onCancel();;
        }
        if ($this->player->getLocation()->getZ() != $this->loc->getZ()) {
            $this->player->sendMessage("Cancelled teleport, don't move!");
            $this->player2->sendMessage("Cancelled teleport, don't move!");
            $this->onCancel();
        }
        if ($this->player2->getLocation()->getX() != $this->loc2->getX()) {
            $this->player->sendMessage("Cancelled teleport, don't move!");
            $this->player2->sendMessage("Cancelled teleport, don't move!");
            $this->onCancel();
        }
        if ($this->player2->getLocation()->getZ() != $this->loc2->getZ()) {
            $this->player->sendMessage("Cancelled teleport, don't move!");
            $this->player2->sendMessage("Cancelled teleport, don't move!");
            $this->onCancel();
        }
        if ($this->count == 0) {
            $this->player2->teleport($this->loc);
            $this->onCancel();
        }
    }
}