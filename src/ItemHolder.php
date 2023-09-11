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
use function _PHPStan_a4fa95a42\RingCentral\Psr7\str;

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
        $this->testCase();
    }

    public function testCase() : void {
        IHolderAPI::register("testcase5", [VanillaItems::DIAMOND()->setCustomName("TEST_CASE1"), VanillaItems::DIAMOND()->setCustomName("2TEST_CASE1")]);
        IHolderAPI::get("testcase5", function (array|Item|null $itemData) {
            var_dump($itemData);
        });
    }

    public function getDatabase() : SQLiteProvider {
        return $this->database;
    }

}