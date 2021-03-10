<?php

namespace Sendarr\Service;

class Base
{
    public $name = "Base";
    
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
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    /**
     * 
     */
    function log(string $name, $variable) : void
    {
        $dateTime =  date('l jS \of F Y h:i:s A');

        $logContent = var_export($variable, true);

        if (is_array($variable)) {
            $logContent = json_encode($variable);
        }
        
        $content = "Log for {$this->name} : {$name} on {$dateTime}:\n\n";
        $content.=$logContent;
        $content.="\n\n";
        $content.="=~=~=~=~\n";   
    
        file_put_contents(
            DIRECTORY_LOG . "/{$this->name}.log",
            $content,
            FILE_APPEND
        );
    }
}