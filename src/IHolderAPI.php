<?php

declare(strict_types=1);

namespace phuongaz\itemholder;

use Closure;
use Exception;
use pocketmine\item\Item;
use SOFe\AwaitGenerator\Await;

final class IHolderAPI {

    public static function register(string $prefix, Item|array $itemData, ?\Closure $callback = null) : void {
        $db = ItemHolder::getInstance()->getDatabase();
        Await::f2c(function() use ($db, $prefix, $itemData, $callback) {
            yield $db->awaitRegister($prefix, $itemData, $callback);
        });
    }

    public static function get(string $prefix, Closure $callback) : void {
        $db = ItemHolder::getInstance()->getDatabase();
        Await::f2c(
            /** @throws Exception */
            function () use ($callback, $prefix, $db) {
            $data = yield $db->awaitGet($prefix);
            $callback($data);
        });
    }
}
