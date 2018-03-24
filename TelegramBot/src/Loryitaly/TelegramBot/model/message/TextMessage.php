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
 * @author ChalkPE <loryitaly@outlook.it>
 * @since 2018-03-24 20:52
 */

namespace Loryitaly\TelegramBot\model\message;

class TextMessage extends Message {
    /** @var string */
    private $text;

    /**
     * @param Message $message
     * @param string $text
     */
    public function __construct(Message $message, $text){
        parent::__construct($message, null, null);
        $this->text = $text;
    }

    /**
     * @param array $array
     * @return TextMessage
     */
    public static function create(array $array){
        return new TextMessage(Message::create($array, false), $array['text']);
    }

    /**
     * @return string
     */
    public function getText(){
        return $this->text;
    }

    /**
     * @return bool
     */
    public function isCommand(){
        return strpos($this->getText(), '/') === 0;
    }

    /**
     * @return string[]|null
     */
    public function getCommands(){
        return $this->isCommand() ? explode(' ', substr($this->getText(), 1)) : null;
    }
}