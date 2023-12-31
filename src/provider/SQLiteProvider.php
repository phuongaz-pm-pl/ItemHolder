<?php

declare(strict_types=1);

namespace phuongaz\itemholder\provider;

use Exception;
use Generator;
use pocketmine\item\Item;
use pocketmine\nbt\LittleEndianNbtSerializer;
use pocketmine\nbt\TreeRoot;
use poggit\libasynql\DataConnector;
use SOFe\AwaitGenerator\Await;

class SQLiteProvider {
    CONST INIT = "holders.init";
    const INSERT = "holders.insert";
    const SELECT = "holders.select";
    const UPDATE = "holders.update";
    const DELETE = "holders.delete";

    private LittleEndianNbtSerializer $serializer;

    public function __construct(private DataConnector $connector) {
        Await::f2c(fn() => $this->connector->asyncGeneric(self::INIT));
        $this->serializer = new LittleEndianNbtSerializer();
    }

    public function getConnector() : DataConnector {
        return $this->connector;
    }

    public function awaitRegister(string $prefix, array|Item $item, ?\Closure $callback = null) : Generator {
        $connector = $this->getConnector();
        $rows = yield from $connector->asyncSelect(self::SELECT, ["prefix" => $prefix]);
        $isRegister = false;

        if(empty($rows)) {
            $itemData = $this->encodeItemData((is_array($item) ? $item : [$item]));
            yield $connector->asyncInsert(self::INSERT, ["prefix" => $prefix, "item_data" => $itemData]);
            $isRegister = true;
        }

        if(!is_null($callback)) {
            $callback($isRegister);
        }

        return $isRegister;
    }

    public function awaitGet(string $prefix): Generator {
        $connector = $this->getConnector();
        $rows = yield from $connector->asyncSelect(self::SELECT, ["prefix" => $prefix]);

        if (empty($rows)) {
            return null;
        }

        $itemData = $this->decodeItemDataRaw($rows[0]["item_data"]);

        if (count($itemData) == 1) {
            $itemData = $itemData[0];
        }

        return $itemData;
    }

    /**
     * @throws Exception
     */
    public function awaitUpdate(string $prefix, Item|array $itemData, ?\Closure $callback = null) : Generator {
        $connector = $this->getConnector();
        $rows = yield from $connector->asyncSelect(self::SELECT, ["prefix" => $prefix]);

        if (empty($rows)) {
            return null;
        }
        $itemData = $this->encodeItemData((is_array($itemData) ? $itemData : [$itemData]));
        yield $connector->asyncChange(self::UPDATE, ["prefix" => $prefix, "item_data" => $itemData]);
        if(!is_null($callback)) $callback($itemData);
    }

    public function delete(string $prefix, ?\Closure $callback = null) : Generator {
        $connector = $this->getConnector();
        $rows = yield from $connector->asyncSelect(self::SELECT, ["prefix" => $prefix]);
        if(!empty($rows)) {
            yield $connector->asyncGeneric(self::DELETE, ["prefix" => $prefix]);
        }

        if(!is_null($callback)) {
            $callback(empty($rows));
        }
    }

    private function encodeItemData(array $itemData) : string {
        $itemsDataRaw = array_map(function(Item $item) {
            return base64_encode($this->serializer->write(new TreeRoot($item->nbtSerialize())));
        }, $itemData);
        return json_encode($itemsDataRaw);
    }

    private function decodeItemDataRaw(string $dataRaw) : array {
        $dataRaw = json_decode($dataRaw, true);
        return array_map(function(string $data) {
            return Item::nbtDeserialize($this->serializer->read(base64_decode($data))->mustGetCompoundTag());
        }, $dataRaw);
    }
}