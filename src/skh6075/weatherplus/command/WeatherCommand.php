<?php

namespace skh6075\weatherplus\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use skh6075\weatherplus\session\Weather;
use skh6075\weatherplus\session\WeatherIds;
use skh6075\weatherplus\session\WeatherSession;
use skh6075\weatherplus\WeatherPlus;

final class WeatherCommand extends Command{

    private WeatherPlus $plugin;

    public function __construct(WeatherPlus $plugin) {
        parent::__construct("weather", "weather command.");
        $this->setPermission("weather.permission");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $player, string $label, array $args): bool{
        if (!$player instanceof Player) {
            $player->sendMessage(TextFormat::RED . "Please, this command use only in-game");
            return false;
        }

        if (!$this->testPermission($player)) {
            return false;
        }

        $type = strtolower(array_shift($args) ?? "");
        if (trim($type) === "") {
            $player->sendMessage("/" . $this->getName() . " [" . implode("|", array_keys(WeatherIds::getWeathers())) . "]");
            return false;
        }

        $session = WeatherSession::getWeatherSession($world = $player->getWorld());
        if (!$session instanceof Weather) {
            WeatherSession::registerWeatherSession($world);
        }

        $session->setWeather(WeatherIds::getNameByWeatherId($type));
        $player->sendMessage("Change World Weather.");
        return true;
    }
}