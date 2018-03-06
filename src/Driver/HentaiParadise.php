<?php

namespace Yamete\Driver;

use \Tuna\CloudflareMiddleware;

class HentaiParadise extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai-paradise.fr';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/doujins/(?<album>[^?/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $this->sUrl = 'https://' . self::DOMAIN . '/doujins/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $this->sUrl . '0');
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.goPage a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $oImg = $this->getDomParser()
                ->load(
                    (string)$this->getClient()->request('GET', $this->sUrl . $oLink->getAttribute('href'))->getBody()
                )
                ->find('#fullPage img');
            $sFilename = $oImg->getAttribute('src');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    public function getClient($aOptions = [])
    {
        $oClient = new \GuzzleHttp\Client(
            ['cookies' => new \GuzzleHttp\Cookie\FileCookieJar(tempnam('/tmp', __CLASS__))]
        );
        $oHandler = $oClient->getConfig('handler');
        $oHandler->push(CloudflareMiddleware::create());
        return $oClient;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
