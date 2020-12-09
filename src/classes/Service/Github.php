<?php

namespace Sendarr\Service;

class Github
{
   
    public $name = "Github";

    function __construct()
    {
        
        $this->data = $this->getInputData();

        $this->log($this->data);
        $this->log($_GET);
        $this->log($_POST);
    }

    function getStatus() : string
    {
        return "Something happened on github. See the logs!";
    }
}