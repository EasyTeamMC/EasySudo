<?php

/*
 *
 *  _____               _____                    __  __  ____
 * | ____|__ _ ___ _   |_   _|__  __ _ _ __ ___ |  \/  |/ ___|
 * |  _| / _` / __| | | || |/ _ \/ _` | '_ ` _ \| |\/| | |
 * | |__| (_| \__ \ |_| || |  __/ (_| | | | | | | |  | | |___
 * |_____\__,_|___/\__, ||_|\___|\__,_|_| |_| |_|_|  |_|\____|
 *                 |___/
 *
 *
 * Copyright 2021 EasyTeamMC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 *
 * @author EasyTeamMC
 * @link https://github.com/EasyTeamMC/EasySudo
 *
 */

declare(strict_types=1);

namespace easyteammc\easysudo;

use easyteammc\easysudo\commands\SudoCommand;
use easyteammc\easysudo\singleton\SingletonTrait;
use JackMD\ConfigUpdater\ConfigUpdater;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class EasySudo extends PluginBase {
    use SingletonTrait;

    public const LATEST_CONFIG_VERSION = 1;

    public function onLoad() {
        $this->init();
    }

    public function onEnable() {
        $this->saveDefaultConfig();
        Server::getInstance()->getCommandMap()->register($this->getName(), new SudoCommand("sudo"));
        ConfigUpdater::checkUpdate($this, $this->getConfig(), "config-version", self::LATEST_CONFIG_VERSION);
        //UpdateNotifier::checkUpdate($this->getName(), $this->getDescription()->getVersion());
    }

    public function isFormEnable(): bool {
        return $this->getConfig()->get("form-enable", false);
    }

}