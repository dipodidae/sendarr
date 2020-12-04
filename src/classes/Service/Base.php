<?php

namespace Sendarr\Service;

class Base
{
    public $data;

    /**
     * 
     */
    private $icon = '🚀';

    /**
     * 
     */
    function validate() : bool
    {
        return true;
    }

    /**
     * 
     */
    function getStatus() : string
    {
        return  '';
    }

    /**
     * 
     */
    function getReadableFilesize(int $size) : string
    {
        return (new \ScriptFUSION\Byte\ByteFormatter)
            ->setPrecision(2)
            ->setBase(\ScriptFUSION\Byte\Base::DECIMAL)
            ->format($size);
    }

    /**
     * 
     */
    function setIcons() : void
    {
        $this->data['emojiIcon'] = $this->icon;
        $this->data['emojiRandom'] = (new \MarufMax\Emoticon\Emoticon)->random();
    }

    /**
     * 
     */
    function getInputData() : array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}