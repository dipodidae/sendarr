<?php

namespace Sendarr;

class Helpers
{

    /**
     * 
     */
    static function getReadableFilesize(int $size) : string
    {
        return (new \ScriptFUSION\Byte\ByteFormatter)
            ->setPrecision(2)
            ->setBase(\ScriptFUSION\Byte\Base::DECIMAL)
            ->format($size);
    }
}