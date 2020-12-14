<?php

namespace Sendarr\Service;

class Radarr extends Base
{
    
    public $name = "Radarr";

    /**
     * 
     */
    public $data;
    
    /**
     * 
     */
    public $icon = '🎥';
    
    /**
     * 
     */
    private $engine;

    /**
     * 
     */
    private $imdb;

    /**
     * 
     */
    private $stringTemplates = [
        'Grab' => "{emojiIcon} ↝ {emojiRandom} Started downloading ‘{remoteMovie.title}’ ({remoteMovie.year}) ({release.quality} - {release.sizeReadable}) - ⭐{remoteMovie.rating}",
        'Download' => "{emojiIcon} ↝ {emojiRandom} Downloaded ‘{remoteMovie.title}’ ({remoteMovie.year}) (https://www.imdb.com/title/{remoteMovie.imdbId}/) - ⭐{remoteMovie.rating} 🎉",
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

        if (isset($this->data['remoteMovie'])) {
            $this->imdb = new \Imdb\Title(preg_replace('/^tt/', '', $this->data['remoteMovie']['imdbId']));
            $this->data['remoteMove']['rating'] = $this->imdb->getRating();
        }
        
        if ($this->data['eventType'] === 'Grab') {
            $this->data['release']['sizeReadable'] = \Sendarr\Helpers::getReadableFilesize(intval($this->data['release']['size']));
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