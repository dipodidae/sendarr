<?php

class TweetSonar
{

    /**
     * 
     */
    private $data;
    
    /**
     * 
     */
    private $icon = '📺';
    
    /**
     * 
     */
    private $engine;

    /**
     * 
     */
    private $stringTemplates = [
        'Grab' => "{emojiIcon} ↝ {emojiRandom} Started downloading ‘{series.title}’ ({episodeList}) ({release.quality} - {release.sizeReadable})",
        'Download' => "{emojiIcon} ↝ {emojiRandom} Downloaded ‘{series.title}’ ({episodeList}) (https://www.thetvdb.com/?id={series.tvdbId}&tab=series) 🎉",
        'Rename' => "{emojiIcon} ↝ {emojiRandom} Renamed ‘{series.title}’",
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

        if (in_array($this->data['eventType'], ['Grab', 'Download'])) {
            $this->data['episodeList'] = $this->getEpisodeList();
        }

    }

    function validate() : bool
    {

        foreach(['series', 'episodes'] as $requiredKey) {
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
    function getEpisodeList() : string
    {
        $renderedEpisodes = array_map(
            function ($episode) {
                return $this->engine->render("S{seasonNumber}, e{episodeNumber}", $episode); 
            },
            $this->data['episodes']
        );


        return implode(", ", $renderedEpisodes);
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

return (new TweetSonar)->parse();
