<?php

namespace Sendarr\Service;

class Rest extends Base
{
    function __construct()
    {
        $this->log("GET", $_GET);
    }

    /**
     * 
     */
    function getStatus()
    {
        return $_GET['status'] ?? "Error: no status received";
    }
}