<?php

namespace Sendarr\Service;

class Rest
{
    /**
     * 
     */
    function getStatus()
    {
        return $_GET['status'] ?? "Error: no status received";
    }
}