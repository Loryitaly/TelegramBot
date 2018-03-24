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
 * @since 2018-03-24 20:47
 */

namespace Loryitaly\TelegramBot\model;

interface Arrayable {
    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $array
     * @return mixed
     */
    public static function create(array $array);
}