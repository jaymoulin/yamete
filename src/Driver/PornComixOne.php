<?php

namespace Yamete\Driver;

class PornComixOne extends ILikeComixCom
{
    private const DOMAIN = 'porncomix.one';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.']) . ')/(?<category>gallery)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Domain to download on
     * @return string
     */
    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
