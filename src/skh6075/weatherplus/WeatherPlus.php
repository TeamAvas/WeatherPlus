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
use skh6075\weatherplus\weather\Weather;
use skh6075\weatherplus\weather\WeatherIds;
use skh6075\weatherplus\weather\WeatherUpdatedTask;

final class WeatherPlus extends PluginBase{
    use SingletonTrait;

    /** @var Weather[] */
    private static array $weathers = [];

    protected function onLoad(): void{
        self::setInstance($this);

        EntityFactory::getInstance()->register(LightningBolt::class, function (World $world, CompoundTag $nbt): LightningBolt{
            return new LightningBolt(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ["LightningBolt"], EntityLegacyIds::LIGHTNING_BOLT);
    }

    protected function onEnable(): void{
        foreach ($this->getServer()->getWorldManager()->getWorlds() as $world) {
            self::$weathers[$world->getId()] = new Weather($world);
        }

        $this->getServer()->getCommandMap()->register(strtolower($this->getName()), new WeatherCommand($this));
        $this->getScheduler()->scheduleRepeatingTask(new WeatherUpdatedTask($this), 20);
    }

    public function getWeatherByWorld(World $world): ?Weather{
        return self::$weathers[$world->getId()] ?? null;
    }

    public function registerWorld(World $world): void{
        self::$weathers[$world->getId()] = new Weather($world);
    }

    public function unregisterWorld(World $world): void{
        if (isset(self::$weathers[$world->getId()])) {
            $weather = $this->getWeatherByWorld($world);
            $weather->setWeather(WeatherIds::SUNNY, 12000);
            unset(self::$weathers[$world->getId()]);
        }
    }

    public function getWeathers(): array{
        return self::$weathers;
    }
}
