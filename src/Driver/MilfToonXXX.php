<?php

namespace Yamete\Driver;

class MilfToonXXX extends AllPornComicCom
{
    private $aMatches = [];
    const DOMAIN = 'milftoon.xxx';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/comics/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }
}
