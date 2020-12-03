<?php

class TweetRadarr
{

    /**
     * 
     */
    private $data;
    
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
        $this->engine = new StringTemplate\Engine;
        
        $this->setIcons();
        
        if (!$this->validate()) {
            return;
        }
        
        if ($this->data['eventType'] === 'Grab') {
            $this->data['release']['sizeReadable'] = $this->getReadableFilesize();
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
    function getInputData() : array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    /**
     * 
     */
    function setIcons() : void
    {
        $this->data['emojiIcon'] = '📺';
        $this->data['emojiRandom'] = (new \MarufMax\Emoticon\Emoticon)->random();
    }

    /**
     * 
     */
    function getReadableFilesize() : string
    {
        return (new ScriptFUSION\Byte\ByteFormatter)
            ->setBase(ScriptFUSION\Byte\Base::DECIMAL)
            ->format($this->data['release']['size']);
    }

    /**
     * 
     */
    function parse() : string
    {
        return $this->engine->render($this->stringTemplates[$this->data['eventType']], $this->data);
    }
}

return (new TweetRadarr)->parse();
