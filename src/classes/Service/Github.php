<?php

namespace Sendarr\Service;

class Github extends Base
{
   
    public $name = "Github";

    function __construct()
    {
        
        $this->data = $this->getInputData();

        $this->log("Data", $this->data);
        $this->log("GET", $_GET);
        $this->log("POST", $_POST);
    }

    function getStatus() : string
    {
        return (new \MarufMax\Emoticon\Emoticon)->random()
                . "Something happened on github. See the logs!";
    }
}