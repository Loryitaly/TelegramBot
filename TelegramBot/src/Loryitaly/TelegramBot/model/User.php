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
 * @since 2018-03-24 20:36
 */

namespace Loryitaly\TelegramBot\model;

class User extends Model implements Identifiable, Nameable {
    /** @var int */
    private $id;

    /** @var string */
    private $firstName;

    /** @var string|null */
    private $lastName = null;

    /** @var string|null */
    private $username = null;

    /**
     * @param int $id
     * @param string $firstName
     * @param string|null $lastName
     * @param string|null $username
     */
    public function __construct($id, $firstName, $lastName = null, $username = null){
        parent::__construct();

        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->username = $username;
    }

    /**
     * @param array $array
     * @return User
     */
    public static function create(array $array){
        return new User(intval($array['id']), $array['first_name'],
            isset($array['last_name']) ? $array['last_name'] : null,
            isset($array['username'])  ? $array['username']  : null);
    }

    /**
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(){
        return $this->firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(){
        return $this->lastName;
    }

    /**
     * @return string|null
     */
    public function getUsername(){
        return $this->username;
    }

    /**
     * @return string
     */
    public function getFullName(){
        return ($this->getLastName() === null) ? $this->getFirstName() : $this->getFirstName() . " " . $this->getLastName();
    }
}