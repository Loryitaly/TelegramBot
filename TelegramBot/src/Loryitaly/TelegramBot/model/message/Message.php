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
 * @author ChalkPE <loryitaly@outlook.com>
 * @since 2018-03-24 20:48
 */

namespace Loryitaly\TelegramBot\model\message;

use Loryitaly\TelegramBot\model\Chat;
use Loryitaly\TelegramBot\model\Identifiable;
use Loryitaly\TelegramBot\model\Model;
use Loryitaly\TelegramBot\model\User;

class Message extends Model implements Identifiable {
    /** @var int */
    private $messageId;

    /** @var int */
    private $date;

    /** @var Chat */
    private $chat;

    /** @var User|null */
    private $from = null;

    /** @var User|null */
    private $forwardFrom = null;

    /** @var int|null */
    private $forwardDate = null;

    /** @var Message|null */
    private $replyToMessage = null;

    /**
     * @param Message|int $messageId
     * @param int $date
     * @param Chat $chat
     * @param User|null $from
     * @param User|null $forwardFrom
     * @param int|null $forwardDate
     * @param Message|null $replyToMessage
     */
    public function __construct($messageId, $date, $chat, User $from = null, User $forwardFrom = null, $forwardDate = null, Message $replyToMessage = null){
        parent::__construct();

        if($messageId instanceof Message){
            $date = $messageId->getDate();
            $chat = $messageId->getChat();
            $from = $messageId->getFrom();
            $forwardFrom = $messageId->getForwardFrom();
            $forwardDate = $messageId->getForwardDate();
            $replyToMessage = $messageId->getReplyToMessage();
            $messageId = $messageId->getMessageId();
        }

        $this->messageId = $messageId;
        $this->date = $date;
        $this->chat = $chat;
        $this->from = $from;
        $this->forwardFrom = $forwardFrom;
        $this->forwardDate = $forwardDate;
        $this->replyToMessage = $replyToMessage;
    }

    /**
     * @param array $array
     * @param bool $cast
     * @return Message|TextMessage|PhotoMessage
     */
    public static function create(array $array, $cast = true){
        if($cast and isset($array['text'])) return TextMessage::create($array);
        if($cast and isset($array['photo'])) return PhotoMessage::create($array);

        return new Message(intval($array['message_id']), intval($array['date']), Chat::create($array['chat']),
            isset($array['from'])             ? User::create($array['from'])                : null,
            isset($array['forward_from'])     ? User::create($array['forward_from'])        : null,
            isset($array['forward_date'])     ? intval($array['forward_date'])              : null,
            isset($array['reply_to_message']) ? Message::create($array['reply_to_message']) : null);
    }

    /**
     * @return int
     */
    public function getId(){
        return $this->getMessageId();
    }

    /**
     * @return int
     */
    public function getMessageId(){
        return $this->messageId;
    }

    /**
     * @return int
     */
    public function getDate(){
        return $this->date;
    }

    /**
     * @return Chat
     */
    public function getChat(){
        return $this->chat;
    }

    /**
     * @return User|null
     */
    public function getFrom(){
        return $this->from;
    }

    /**
     * @return User|null
     */
    public function getForwardFrom(){
        return $this->forwardFrom;
    }

    /**
     * @return int|null
     */
    public function getForwardDate(){
        return $this->forwardDate;
    }

    /**
     * @return Message|null
     */
    public function getReplyToMessage(){
        return $this->replyToMessage;
    }
}