<?php

namespace skh6075\weatherplus\session;

use pocketmine\world\World;

final class WeatherSession{

    /** @var Weather[] */
    private static array $worlds = [];

    public static function getWeatherSession(World $world): ?Weather{
        return self::$worlds[$world->getFolderName()] ?? null;
    }

    public static function registerWeatherSession(World $world): void{
        self::$worlds[$world->getFolderName()] = new Weather($world);
    }

    public static function unregisterWeatherSession(World $world): void{
        if (isset(self::$worlds[$world->getFolderName()])) {
            unset(self::$worlds[$world->getFolderName()]);
        }
    }
}