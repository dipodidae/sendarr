<?php

namespace Sendarr\Service;

class Lidarr extends Base
{

    public $name = "Lidarr";

    /**
     * 
     */
    public $data;
    
    /**
     * 
     */
    public $icon = '🎵';
    
    /**
     * 
     */
    private $engine;

    /**
     * 
     */
    private $stringTemplates = [
        'Grab' => "{emojiIcon} ↝ {emojiRandom} Started downloading {albumList} ({release.quality} - {release.sizeReadable})",
        'Download' => "{emojiIcon} ↝ {emojiRandom} Downloaded {albumList} 🎉",
        'Rename' => "{emojiIcon} ↝ {emojiRandom} Renamed",
        'Test' => "{emojiIcon} ↝ {emojiRandom} Testie!"
    ];

    /**
     * 
     */
    function __construct()
    {
        
        $this->data = $this->getInputData();
        $this->engine = new \StringTemplate\Engine;

        $this->log("Data", $this->data);
        
        $this->setIcons();
        
        if ($this->data['eventType'] === 'Grab') {
            $this->data['release']['sizeReadable'] = \Sendarr\Helpers::getReadableFilesize(intval($this->data['release']['size']));
        }

        if (in_array($this->data['eventType'], ['Grab', 'Download'])) {
            $this->data['albumList'] = $this->getAlbumList();
        }
    }

    /**
     * 
     */
    function getAlbumList() : string
    {
        $renderedAlbums = array_map(
            function ($album) {

                return $this->engine->render(
                    "{artist} - {title} ({date})",
                    [
                        'artist' => $this->data['artist']['name'],
                        'title' => $album['title'],
                        'date' => date('Y', strtotime($album['releaseDate']))
                    ]
                ); 
            },
            $this->data['albums']
        );


        return implode(", ", $renderedAlbums);
    }

    /**
     * 
     */
    function getStatus() : string
    {
        return $this->engine->render($this->stringTemplates[$this->data['eventType']], $this->data);
    }
}