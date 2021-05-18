<?php

namespace skh6075\weatherplus\session;

use pocketmine\entity\EntityDataHelper;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\Server;
use pocketmine\world\World;
use skh6075\weatherplus\entity\LightningBolt;

final class Weather{

    private World $world;

    private int $weatherId = WeatherIds::SUNNY;

    private int $lastUpdate = 0;

    private int $duration = 1200;

    public function __construct(World $world) {
        $this->world = $world;
        $this->lastUpdate = $this->world->getServer()->getTick();
    }

    public function setWeather(int $weatherId, int $duration = 12000): void{
        $this->weatherId = $weatherId;
        $this->duration = $duration;
        $this->sendWeatherPackets();
    }

    public function sendWeatherPackets(): void{
        $pk1 = LevelEventPacket::create(LevelEventPacket::EVENT_STOP_RAIN, mt_rand(90000, 110000), null);
        $pk2 = LevelEventPacket::create(LevelEventPacket::EVENT_STOP_THUNDER, mt_rand(30000, 40000), null);

        switch ($this->weatherId) {
            case WeatherIds::RAIN:
            case WeatherIds::RAINY:
                $pk1 = LevelEventPacket::create(LevelEventPacket::EVENT_START_RAIN, $pk1->data, null);
                break;
            case WeatherIds::RAINY_THUNDER:
                $pk1 = LevelEventPacket::create(LevelEventPacket::EVENT_START_RAIN, $pk1->data, null);
                $pk2 = LevelEventPacket::create(LevelEventPacket::EVENT_START_THUNDER, $pk2->data, null);
                break;
            case WeatherIds::THUNDER:
                $pk2 = LevelEventPacket::create(LevelEventPacket::EVENT_START_THUNDER, $pk2->data, null);
                break;
            default:
                break;
        }

        Server::getInstance()->broadcastPackets($this->world->getPlayers(), [$pk1, $pk2]);
    }

    public function onUpdate(int $currentTick): void{
        $tickDiff = $currentTick - $this->lastUpdate;
        $this->duration -= $tickDiff;

        if ($this->duration <= 0) {
            $duration = mt_rand(6000, 12000);
            if ($this->weatherId === WeatherIds::SUNNY) {
                $weather = WeatherIds::getRandomWeather(true);
                $this->setWeather($weather, $duration);
            } else {
                $this->setWeather(WeatherIds::SUNNY, $duration);
            }
        }

        if ($this->weatherId >= WeatherIds::RAINY_THUNDER and is_int($this->duration / 200)) { // RAINY_THUNDER and THUNDER and more...
            $players = $this->world->getPlayers();
            if (count($players) > 0) {
                $player = $players[array_rand($players)];
                $xOffset = $player->getPosition()->getX() + mt_rand(-64, 64);
                $zOffset = $player->getPosition()->getZ() + mt_rand(-64, 64);
                $highY = $this->world->getHighestBlockAt((int) $xOffset, (int) $zOffset);

                $nbt = EntityDataHelper::createBaseNBT($spawnVec = new Vector3($xOffset, $highY, $zOffset));
                $entity = new LightningBolt(EntityDataHelper::parseLocation($nbt, $this->world), $nbt);
                $entity->spawnToAll();
            }
        }
        
        $this->lastUpdate = $currentTick;
    }
}
