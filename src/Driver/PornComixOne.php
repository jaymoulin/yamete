<?php

namespace Yamete\Driver;

class PornComixOne extends ILikeComixCom
{
    private $aMatches = [];
    const DOMAIN = 'porncomix.one';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/gallery/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }
}
