<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class BestPornComixCom extends CartoonSexComixCom
{
    private const DOMAIN = 'bestporncomix.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return 'figure a';
    }
}
