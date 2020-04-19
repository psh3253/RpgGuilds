<?php

namespace astar\RpgGuilds;

use pocketmine\level\Location;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class HQTeleportTask extends Task
{
    /**
     * @var RpgGuilds
     */
    private $owner;

    /**
     * @var Player
     */
    private $p;

    /**
     * @var Location
     */
    private $gloc2;

    /**
     * @var Location
     */
    private $ploc;

    /**
     * @var int
     */
    private $i;


    public function __construct(RpgGuilds $owner, Player $p, Location $gloc2, Location $ploc)
    {
        $this->i = 5;
        $this->owner = $owner;
        $this->p = $p;
        $this->gloc2 = $gloc2;
        $this->ploc = $ploc;
    }

    public function onRun(int $currentTick)
    {
        $this->p->sendMessage("§2길드본부 순간이동 타이머: §4" . $this->i);
        if ($this->i == 0) {
            $this->p->teleport($this->gloc2);
            $this->onCancel();
            return;
        }
        if ($this->p->getLocation()->getX() != $this->ploc->getX()) {
            $this->p->sendMessage("길드 홀에 입장하려면 한 곳에 서 있어야합니다!");
            $this->onCancel();
            return;
        }
        if ($this->p->getLocation()->getY() != $this->ploc->getY()) {
            $this->p->sendMessage("길드 홀에 입장하려면 한 곳에 서 있어야합니다!");
            $this->onCancel();
            return;
        }
        if ($this->p->getLocation()->getZ() != $this->ploc->getZ()) {
            $this->p->sendMessage("길드 홀에 입장하려면 한 곳에 서 있어야합니다!");
            $this->onCancel();
            return;
        }
        --$this->i;
    }
}