# ItemHolder

Simple item storage plugin for PocketMine.

### Usage:
```php

// register 
IHolderAPI::register("diamond_custom", VanillaItems::DIAMOND()->setCustomName("Diamond Custom"));

// get
IHolderAPI::get("diamond_custom", function($itemData) {
    var_dump($itemData);
});
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