<?php

namespace skh6075\weatherplus\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use skh6075\weatherplus\weather\Weather;
use skh6075\weatherplus\weather\WeatherIds;
use skh6075\weatherplus\WeatherPlus;

final class WeatherCommand extends Command{

    private WeatherPlus $plugin;

    public function __construct(WeatherPlus $plugin) {
        parent::__construct("weather", "weather command.");
        $this->setPermission("weather.permission");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $player, string $label, array $args): bool{
        if (!$player instanceof Player or !$this->testPermission($player)) {
            return false;
        }

        $type = strtolower(array_shift($args) ?? "");
        if (trim($type) === "") {
            $player->sendMessage("/" . $this->getName() . " [" . implode("|", array_keys(WeatherIds::getWeathers())) . "]");
            return false;
        }

        if (!($weather = $this->plugin->getWeatherByWorld($player->getWorld())) instanceof Weather) {
            $this->plugin->registerWorld($player->getWorld());
        }

        $weather->setWeather(WeatherIds::getWeathers()[$type] ?? WeatherIds::SUNNY, 12000);
        $player->sendMessage("Change World Weather.");
        return true;
    }
}
