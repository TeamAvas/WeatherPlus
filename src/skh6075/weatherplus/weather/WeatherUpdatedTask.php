<?php

namespace skh6075\weatherplus\weather;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use skh6075\weatherplus\WeatherPlus;

class WeatherUpdatedTask extends Task{

    private WeatherPlus $plugin;

    public function __construct(WeatherPlus $plugin) {
        $this->plugin = $plugin;
    }

    public function onRun(): void{
        foreach ($this->plugin->getWeathers() as $weather) {
            $weather->onUpdate(Server::getInstance()->getTick());
        }
    }
}