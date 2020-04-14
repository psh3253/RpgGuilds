<?php

namespace astar\RpgGuilds;

use pocketmine\inventory\Inventory;

class Bank
{
    private $inv;

    public function Bank(string $name)
    {
        $this->loadBank($name, $this->inv);
    }

    public static function loadBank(string $name, Inventory $inven)
    {
    }

    public static function saveBank(string $name, Inventory $inven)
    {
    }
}