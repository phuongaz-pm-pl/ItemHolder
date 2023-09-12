# ItemHolder

Simple item storage plugin for PocketMine.

### Usage:
```php


    public function test() : void {
        IHolderAPI::register("testcase1", [VanillaItems::DIAMOND(), VanillaItems::DIAMOND_PICKAXE()], function(bool $isRegister) {
            $this->testupdate();
        });
    }

    public function testupdate() : void {
        IHolderAPI::update("testcase1", [VanillaItems::GOLD_INGOT(), VanillaItems::GOLD_INGOT()], function(string $itemDataRaw) {
            var_dump($itemDataRaw);
            $this->testGet();
        });
    }

    public function testGet() : void {
        IHolderAPI::get("testcase1", function (array|Item $itemData){
            $itemData = (is_array($itemData)) ? $itemData : [$itemData];
            foreach ($itemData as $item) {
                var_dump($item->getName());
            }
        });
    }
```

### Simple BackPack:
```php

$playerName = Player->getName();
$items = Inventory->getContents();

// register 
IHolderAPI::register($playerName, $items);

// get
IHolderAPI::get($playerName, function($itemData) {
    if(is_array($item)) {
        Inventory->setContents($item)
    }
});

```