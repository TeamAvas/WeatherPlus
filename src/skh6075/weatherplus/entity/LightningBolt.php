<?php

namespace skh6075\weatherplus\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\Explosion;
use pocketmine\world\sound\ExplodeSound;

final class LightningBolt extends Entity{

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
        $ev = new ExplosionPrimeEvent($this, 2.3);
        $ev->call();
        if (!$ev->isCancelled()) {
            $explosion = new Explosion($this->getPosition(), $ev->getForce());
            if ($ev->isBlockBreaking()) {
                $explosion->explodeA();
            }

            $explosion->explodeB();

            $this->broadcastSound(new ExplodeSound());
        }

        return parent::onUpdate($currentTick);
    }
}
