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

namespace easyteammc\easysudo\commands;

use easyteammc\easysudo\EasySudo;
use easyteammc\easysudo\event\PlayerSudoEvent;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\command\utils\NoSelectorMatchException;
use pocketmine\Player;
use pocketmine\Server;

class SudoCommand extends Command {

    public function __construct(string $name) {
        parent::__construct($name, "", "/sudo <player: target> [Command / Message]", [], null);
        $this->setPermission("easysudo.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) return;
        if (!$sender instanceof Player) {
            if (count($args) < 2) {
                throw new InvalidCommandSyntaxException();
            }
            $selectedTarget = Server::getInstance()->getPlayer(array_shift($args));
            if ($selectedTarget instanceof Player) {
                $this->executeSudo($sender, $selectedTarget, trim(implode(" ", $args)));
            }else {
                throw new NoSelectorMatchException();
            }
            return;
        }
        if (EasySudo::getInstance()->isFormEnable()) {
            $this->sendForm($sender);
        }else {
            if (count($args) < 2) {
                throw new InvalidCommandSyntaxException();
            }
            $selectedTarget = Server::getInstance()->getPlayer(array_shift($args));
            if ($selectedTarget instanceof Player) {
                $this->executeSudo($sender, $selectedTarget, trim(implode(" ", $args)));
            }else {
                throw new NoSelectorMatchException();
            }
        }
    }

    private function sendForm(Player $player): void {
        $playerList = array_map(function(Player $players): string{
            return $players->getName();
        }, array_filter(Server::getInstance()->getOnlinePlayers(), function(Player $players) use ($player): bool{
            return $players->isOnline() and (!($player instanceof Player) or $player->canSee($players));
        }));
        sort($playerList, SORT_STRING);
        $form = new CustomForm(function (Player $player, $data = null) use ($playerList): void {
            if ($data === null) return;
            $selectedTarget = Server::getInstance()->getPlayer($data[0]);
            $cmd = $data[1];
            $this->executeSudo($player, $selectedTarget, $cmd);
        });
        $form->setTitle("Sudo Menu");
        $form->addDropdown("Select players", $playerList);
        $form->addInput("Command / Message", "Hello world");
        $form->sendToPlayer($player);
    }

    private function executeSudo(CommandSender $sender, Player $target, string $chat): void {
        $ev = new PlayerSudoEvent($sender, $target, $chat);
        if ($ev->isCancelled()) return;

        $target->chat($chat);
    }

}