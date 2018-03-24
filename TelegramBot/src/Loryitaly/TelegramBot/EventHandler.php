<?php

/*
 * Copyright (C) 2018  Loryitaly
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @author Loryitaly <loryitaly@outlook.com>
 * @since 2018-03-24 20:22
 */

namespace Loryitaly\TelegramBot;

use Loryitaly\TelegramBot\event\TelegramMessageEvent;
use Loryitaly\TelegramBot\model\message\PhotoMessage;
use Loryitaly\TelegramBot\model\message\TextMessage;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\TranslationContainer;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class EventHandler extends ConsoleCommandSender implements Listener {
    /** @var int[] */
    public static $lastCommand = [];

    public function getName(){
        return "PocketTelegram";
    }

    //PocketMine -> Telegram
    public function sendMessage($message){
        if(PocketTelegram::$broadcastToTelegram) PocketTelegram::sendMessage($message, PocketTelegram::getDefaultChannel());
    }

    //Telegram -> PocketMine
    public function onTelegramMessage(TelegramMessageEvent $event){
        if(!PocketTelegram::$broadcastTelegramMessages) return;

        $message = $event->getMessage();
        switch(true){
            case $message instanceof TextMessage:
                if(PocketTelegram::$enableTelegramCommands and $message->isCommand()){
                    $this->handleTelegramCommands($message);
                    return;
                }

                $text = $message->getText();
                break;

            case $message instanceof PhotoMessage:
                $text = "(Photo)";
                break;

            default: return;
        }

        if($message->getChat()->getId() !== PocketTelegram::getDefaultChannel()) return;
        if(is_null($from = $message->getFrom()) or is_null($username = $from->getUsername())) return;

        $this->broadcastMessage(PocketTelegram::getInstance()->getConfig()->get("telegramUserPrefix", "@") . $username, TextFormat::clean($text));
    }

    /**
     * @param TextMessage $message
     */
    private function handleTelegramCommands(TextMessage $message){
        if(time() - $message->getDate() > 30) return;

        $chatId = $message->getChat()->getId();
        if(!isset(self::$lastCommand[$chatId])) self::$lastCommand[$chatId] = 0;
        if((time() - self::$lastCommand[$chatId]) < 2) return;

        $commands = $message->getCommands();
        if(count($command = explode('@', $commands[0])) > 1){
            if(!is_null($me = PocketTelegram::getMe()) and strToLower($command[1]) !== strToLower($me->getUsername())) return;
            $commands[0] = $command[0];
        }

        switch(strToLower($commands[0])){
            case "chat_id":
                PocketTelegram::sendMessage($chatId, $message->getChat(), $message);
                break;

            case "online":
                $players = array_map(function(Player $player){ return $player->getDisplayName(); }, array_filter(Server::getInstance()->getOnlinePlayers(), function(Player $player){ return $player->isOnline(); }));
                PocketTelegram::sendMessage(PocketTelegram::translateString("commands.players.list", [count($players), Server::getInstance()->getMaxPlayers()]) . PHP_EOL . implode(", " , $players), $message->getChat(), $message);
                break;

            case "stop":
                if(!is_null($from = $message->getFrom()) and !is_null($username = $from->getUsername()) and Server::getInstance()->getOfflinePlayer($username)->isOp()){
                    Command::broadcastCommandMessage($this, new TranslationContainer("commands.stop.start"));
                    Server::getInstance()->shutdown();
                }
                break;
        }
        self::$lastCommand[$chatId] = time();
    }

    private function broadcastMessage($username, $message){
        $recipients = array_filter(Server::getInstance()->getPluginManager()->getPermissionSubscriptions(Server::BROADCAST_CHANNEL_USERS), function($recipient){ return !($recipient instanceof EventHandler); });
        $lines = explode("\n", $message);

        foreach($lines as $line) Server::getInstance()->broadcastMessage(PocketTelegram::translateString("chat.type.text", [$username, $line]), $recipients);
    }

    public function onPlayerJoin(PlayerJoinEvent $event){
        $event->getPlayer()->setDisplayName(PocketTelegram::getInstance()->getConfig()->get("minecraftUserPrefix", "~") . $event->getPlayer()->getDisplayName());
    }

    public function onPlayerQuit(PlayerQuitEvent $event){
        $event->getPlayer()->setDisplayName(substr($event->getPlayer()->getDisplayName(), strlen(PocketTelegram::getInstance()->getConfig()->get("minecraftUserPrefix", "~"))));
        $event->setQuitMessage($event->getPlayer()->getLeaveMessage());
    }
}