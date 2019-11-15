<?php

namespace Yamete\Driver;

class BallBallictCom extends HentaiRulesNet
{
    const DOMAIN = 'ballballict.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @param array $aOptions
     * @return \GuzzleHttp\Client
     */
    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        $oClient = parent::getClient(
            [
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
        /**
         * @var \GuzzleHttp\HandlerStack $oHandler
         */
        return $oClient;
    }
}
