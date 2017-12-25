<?php

namespace Yamete\Driver;

class ThreeDCartoonsNet extends ThreeDSexToonsNet
{
    const DOMAIN = '3dcartoons.net';

    protected function getDomain()
    {
        return self::DOMAIN;
    }
}
