<?php

namespace skh6075\weatherplus;

use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use skh6075\weatherplus\command\WeatherCommand;
use skh6075\weatherplus\entity\LightningBolt;

final class WeatherPlus extends PluginBase{
    use SingletonTrait;

    protected function onLoad(): void{
        self::setInstance($this);

        EntityFactory::getInstance()->register(LightningBolt::class, function (World $world, CompoundTag $nbt): LightningBolt{
            return new LightningBolt(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ["LightningBolt"], EntityLegacyIds::LIGHTNING_BOLT);
    }

    protected function onEnable(): void{
        $this->getServer()->getCommandMap()->register(strtolower($this->getName()), new WeatherCommand($this));
    }
}