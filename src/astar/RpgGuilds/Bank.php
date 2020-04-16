<?php

namespace astar\RpgGuilds;

use pocketmine\inventory\Inventory;

class Bank
{
    private $inv;

    /**
     * @var gTP
     */
    private static $instance = null;

    /**
     * @var RpgGuilds
     */
    private $plugin;

    public function __construct(RpgGuilds $plugin, string $name)
    {
        if(self::$instance == null)
            self::$instance = $this;

        $this->plugin = $plugin;
        $this->loadBank($name, $this->inv);
    }

    public static function loadBank(string $name, Inventory $inven)
    {
    }

    public static function saveBank(string $name, Inventory $inven)
    {
    }
}