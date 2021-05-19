<?php

namespace skh6075\weatherplus\weather;

final class WeatherIds{

    public const CLEAR = 0;

    public const SUNNY = 0;

    public const RAIN = 1;

    public const RAINY = 1;

    public const RAINY_THUNDER = 2;

    public const THUNDER = 3;

    public static function getWeathers(): array{
        $res = [];
        $ref = (new \ReflectionClass(self::class))->getConstants();
        foreach ($ref as $key => $value) {
            $res[strtolower($key)] = $value;
        }

        return $res;
    }

    public static function getRandomWeather(bool $noSunny = false): int{
        $weather = array_keys(self::getWeathers())[array_rand(array_keys(self::getWeathers()))];
        $weatherId = self::getWeathers()[$weather] ?? self::SUNNY;
        if ($noSunny and $weatherId === self::SUNNY) {
            return self::getRandomWeather($noSunny);
        }

        return $weatherId;
    }
}