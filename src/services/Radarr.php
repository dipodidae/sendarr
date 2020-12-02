<?php

$data = json_decode(file_get_contents('php://input'));

$data['randomEmoji'] = (new \MarufMax\Emoticon\Emoticon)->random();

if ($data['eventType'] === 'Grab') {
    $data['release']['sizeReadable'] = (new ByteFormatter)->setBase(Base::DECIMAL)->format($data['release']['size']);
}

$engine = new StringTemplate\Engine;

$stringTemplates = [
    'Grab' => "{randomEmoji} Grabbed '{remoteMovie.title}' ({remoteMovie.year}) ({release.quality} - {release.sizeReadable})",
    'Download' => "{randomEmoji} Downloaded '{remoteMovie.title}' ({remoteMovie.year}) (https://www.imdb.com/title/{remoteMovie.imdbId}/)",
    'Rename' => "Renamed '{movie.title}'",
    'Test' => "{randomEmoji} Testie!"
];

$stringTemplates['Upgrade'] = $stringTemplates['Download'];

return $engine->render($stringTemplates[$data['eventType']], $data);