<?php

/**
 * @author Loryitaly <loryitaly@outlook.com>
 * @since 2018-03-24 20:50
 */

namespace Loryitaly\TelegramBot\model\message;

use Loryitaly\TelegramBot\model\PhotoSize;

class PhotoMessage extends Message {
    /** @var PhotoSize[] */
    private $photoSizes = [];

    /**
     * @param Message $message
     * @param PhotoSize[] $photoSizes
     */
    public function __construct(Message $message, array $photoSizes){
        parent::__construct($message, null, null);
        $this->photoSizes = $photoSizes;
    }

    /**
     * @param array $array
     * @return PhotoMessage
     */
    public static function create(array $array){
        return new PhotoMessage(Message::create($array, false), array_map(function($photoSize){
            return PhotoSize::create($photoSize);
        }, $array['photo']));
    }

    /**
     * @return PhotoSize[]
     */
    public function getPhotoSizes(){
        return $this->photoSizes;
    }
}