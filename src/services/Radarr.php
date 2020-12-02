<?php
$data = json_decode(file_get_contents('php://input'), true);

$data['emojiRandom'] = (new \MarufMax\Emoticon\Emoticon)->random();
$data['emojiIcon'] = '🎥';

if ($data['eventType'] === 'Grab') {
    $data['release']['sizeReadable'] = (new ScriptFUSION\Byte\ByteFormatter)->setBase(ScriptFUSION\Byte\Base::DECIMAL)->format($data['release']['size']);
}

$engine = new StringTemplate\Engine;

$stringTemplates = [
    'Grab' => "{emojiIcon} ↝ {emojiRandom} Started downloading ‘{remoteMovie.title}’ ({remoteMovie.year}) ({release.quality} - {release.sizeReadable})",
    'Download' => "{emojiIcon} ↝ {emojiRandom} Downloaded ‘{remoteMovie.title}’ ({remoteMovie.year}) (https://www.imdb.com/title/{remoteMovie.imdbId}/) 🎉",
    'Rename' => "{emojiIcon} ↝ {emojiRandom} Renamed ‘{movie.title}’",
    'Test' => "{emojiIcon} ↝ {emojiRandom} Testie!"
];

$stringTemplates['Upgrade'] = $stringTemplates['Download'];

return $engine->render($stringTemplates[$data['eventType']], $data);