<?php

namespace Yamete\Driver;

use GuzzleRetry\GuzzleRetryMiddleware;

class NHentai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'nhentai.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/g/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $oClient = $this->getClient(['cookies' => new \GuzzleHttp\Cookie\FileCookieJar(tempnam('/tmp', __CLASS__))]);
        /**
         * @var \GuzzleHttp\HandlerStack $oHandler
         */
        $oHandler = $oClient->getConfig('handler');
        $oHandler->push(GuzzleRetryMiddleware::factory());
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $iTotal = count($this->getDomParser()->load((string)$oRes->getBody())->find('a.gallerythumb'));
        for ($i = 1; $i <= $iTotal; $i++) {
            $sFilename = 'https://i.' . self::DOMAIN . '/galleries/' . $this->aMatches['album'] . '/' . $i . '.jpg';
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i, 5, '0', STR_PAD_LEFT) . '-'
                . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
