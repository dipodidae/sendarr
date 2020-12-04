<?php

namespace Sendarr\Service;

class Radarr extends \Sendarr\Service\Base
{

    /**
     * 
     */
    public $data;
    
    /**
     * 
     */
    private $icon = '🎥';
    
    /**
     * 
     */
    private $engine;

    /**
     * 
     */
    private $stringTemplates = [
        'Grab' => "{emojiIcon} ↝ {emojiRandom} Started downloading ‘{remoteMovie.title}’ ({remoteMovie.year}) ({release.quality} - {release.sizeReadable})",
        'Download' => "{emojiIcon} ↝ {emojiRandom} Downloaded ‘{remoteMovie.title}’ ({remoteMovie.year}) (https://www.imdb.com/title/{remoteMovie.imdbId}/) 🎉",
        'Rename' => "{emojiIcon} ↝ {emojiRandom} Renamed ‘{movie.title}’",
        'Test' => "{emojiIcon} ↝ {emojiRandom} Testie!"
    ];

    /**
     * 
     */
    function __construct()
    {
        
        $this->data = $this->getInputData();
        $this->engine = new \StringTemplate\Engine;
        
        $this->setIcons();
        
        if (!$this->validate()) {
            return;
        }
        
        if ($this->data['eventType'] === 'Grab') {
            $this->data['release']['sizeReadable'] = $this->getReadableFilesize(intval($this->data['release']['size']));
        }
    }

    function validate() : bool
    {

        foreach(['movie', 'eventType'] as $requiredKey) {
            if (!array_key_exists($requiredKey, $this->data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 
     */
    private function getStringTemplate() : string
    {
        return $this->stringTemplates[$this->data['eventType']] ?? '';
    }

    /**
     * 
     */
    function getStatus() : string
    {
        return $this->engine->render($this->getStringTemplate(), $this->data);
    }
}