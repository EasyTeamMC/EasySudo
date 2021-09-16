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

namespace easyteammc\easysudo\event;

use pocketmine\command\CommandSender;
use pocketmine\event\Cancellable;
use pocketmine\event\Event;
use pocketmine\Player;

class PlayerSudoEvent extends Event implements Cancellable {

    /** @var CommandSender */
    private $sender;

    /** @var Player */
    private $target;

    /** @var string */
    private $chat;

    public function __construct(CommandSender $sender, Player $target, string $chat) {
        $this->sender = $sender;
        $this->target = $target;
        $this->chat = $chat;
    }

    /**
     * @return CommandSender
     */
    public function getSender(): CommandSender {
        return $this->sender;
    }

    /**
     * @return Player
     */
    public function getTarget(): Player {
        return $this->target;
    }

    /**
     * @return string
     */
    public function getChat(): string {
        return $this->chat;
    }

}