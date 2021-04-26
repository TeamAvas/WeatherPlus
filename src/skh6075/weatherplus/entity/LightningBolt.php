<?php

namespace skh6075\weatherplus\entity;

use pocketmine\entity\Animal;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\Server;

final class LightningBolt extends Animal{

    protected function getInitialSizeInfo(): EntitySizeInfo{
        return new EntitySizeInfo(1.8, 0.3);
    }

    public static function getNetworkTypeId(): string{
        return EntityIds::LIGHTNING_BOLT;
    }

    public function getName(): string{
        return "LightningBolt";
    }

    public function onUpdate(int $currentTick): bool{
        $this->sendSoundPacket();
        return parent::onUpdate($currentTick);
    }

    private function sendSoundPacket(): void{
        $pk = new PlaySoundPacket();
        $pk->soundName = "ambient.weather.lightning.impact";
        [$pk->x, $pk->y, $pk->z] = [$this->getPosition()->getX(), $this->getPosition()->getY(), $this->getPosition()->getZ()];
        $pk->volume = 500;
        $pk->pitch = 1;

        Server::getInstance()->broadcastPackets($this->getWorld()->getPlayers(), [$pk]);
    }
}