<?php

namespace Sendarr\Service;

class Sonarr extends \Sendarr\Service\Base
{

    /**
     * 
     */
    public $data;
    
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
        $this->engine = new \StringTemplate\Engine;
        
        $this->setIcons();
        
        if (!$this->validate()) {
            return;
        }
        
        if ($this->data['eventType'] === 'Grab') {
            $this->data['release']['sizeReadable'] = \Sendarr\Helpers::getReadableFilesize(intval($this->data['release']['size']));
        }

        if (in_array($this->data['eventType'], ['Grab', 'Download'])) {
            $this->data['episodeList'] = $this->getEpisodeList();
        }

    }

    function validate() : bool
    {

        foreach(['series', 'eventType'] as $requiredKey) {
            if (!array_key_exists($requiredKey, $this->data)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 
     */
    function getEpisodeList() : string
    {
        $renderedEpisodes = array_map(
            function ($episode) {

                $paddedEpisodeNumbers = [
                    'season' => str_pad($episode['seasonNumber'], 2, "0", STR_PAD_LEFT),
                    'episode' => str_pad($episode['episodeNumber'], 2, "0", STR_PAD_LEFT)
                ];

                return $this->engine->render("{season}•{episode}", $paddedEpisodeNumbers); 
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
        $this->data['emojiIcon'] = $this->icon;
        $this->data['emojiRandom'] = (new \MarufMax\Emoticon\Emoticon)->random();
    }

    /**
     * 
     */
    function getStatus() : string
    {
        return $this->engine->render($this->stringTemplates[$this->data['eventType']], $this->data);
    }
}