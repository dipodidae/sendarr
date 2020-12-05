<?php

namespace Sendarr\Service;

class Base
{
    public $data;

    public $icon = '[x]';

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