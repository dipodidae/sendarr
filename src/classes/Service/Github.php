<?php

namespace Sendarr\Service;

class Github extends Base
{

    public $icon = "🐱🦑";
   
    public $name = "Github";

    public $data;

    private $stringTemplates = [
        'completed' => "{emojiIcon} ➜ {emojiRandom} Github action. Repo: {repository.name}, branch: {check_suite.head_branch}, status: {check_suite.status}",
    ];


    function __construct()
    {
        
        $this->data = $this->getInputData();
        $this->setIcons();
    }

    /**
     * 
     */
    function setIcons() : void
    {
        $this->data['emojiIcon'] = $this->icon;
        $this->data['emojiRandom'] = (new \MarufMax\Emoticon\Emoticon)->random();
    }

    function getTemplate() : string
    {
        return $this->stringTemplates[$this->data['action']] ?? '';
    }


    function getStatus() : string
    {
        return $this->engine->render($this->getTemplate(), $this->data);
    }
}