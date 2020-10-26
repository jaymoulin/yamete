<?php

namespace Yamete\Driver;

class ThreeDCartoonsNet extends ThreeDSexToonsNet
{
    private const DOMAIN = '3dcartoons.net';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }
}
