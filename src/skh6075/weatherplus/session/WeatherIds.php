<?php

namespace skh6075\weatherplus\session;

final class WeatherIds{

    public const CLEAR = 0;

    public const SUNNY = 0;

    public const RAIN = 1;

    public const RAINY = 1;

    public const RAINY_THUNDER = 2;

    public const THUNDER = 3;

    /**
     * @return array  [type => id]
     */
    public static function getWeathers(): array{
        $arr = (new \ReflectionClass(self::class))->getConstants();
        $res = [];
        foreach ($arr as $weather => $id) {
            $res[strtolower($weather)] = $id;
        }

        return $res;
    }

    public static function getNameByWeatherId(string $name): int{
        $name = strtolower($name);
        $weathers = self::getWeathers();
        return $weathers[$name] ?? "sunny";
    }

    public static function getRandomWeather(bool $noSunny = false): int{
        $weathers = array_keys(self::getWeathers());
        $weather = $weathers[array_rand($weathers)];
        $weatherId = self::getNameByWeatherId($weather);
        if ($noSunny and $weatherId === self::SUNNY) {
            return self::getRandomWeather($noSunny);
        }

        return $weatherId;
    }
}

