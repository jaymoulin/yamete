<?php

namespace Yamete\Driver;

use GuzzleCloudflare\Middleware;
use GuzzleHttp\Cookie\FileCookieJar;

class KingComixCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'kingcomix.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/])/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $aReturn = [];
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.entry-content img') as $oImg) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('src');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
