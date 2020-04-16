<?php
namespace astar\RpgGuilds;

use pocketmine\Server;

class ActionManager{
    /**
     * @var ActionManager
     */
    private static $instance = null;

    /**
     * @var RpgGuilds
     */
    private $plugin;

    public function __construct(RpgGuilds $plugin)
    {
        if(self::$instance == null)
            self::$instance = $this;

        $this->plugin = $plugin;
    }
}