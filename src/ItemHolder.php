<?php

declare(strict_types=1);

namespace phuongaz\itemholder;

use phuongaz\itemholder\provider\SQLiteProvider;
use phuongaz\itemholder\provider\Types;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use poggit\libasynql\libasynql;

class ItemHolder extends PluginBase {
    use SingletonTrait;

    private SQLiteProvider $database;

    public function onLoad(): void {
        self::setInstance($this);
    }

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $connector = libasynql::create($this, $this->getConfig()->get("database"), [
            "sqlite" => "sqlite.sql",
        ]);
        $this->database = new SQLiteProvider($connector);
    }

    public function getDatabase() : SQLiteProvider {
        return $this->database;
    }

}
