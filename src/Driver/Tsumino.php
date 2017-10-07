<?php

namespace Yamete\Driver;

use Tuna\CloudflareMiddleware;

class Tsumino extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'tsumino.com';

    public function canHandle()
    {
        return preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/Book/Info/(?<album>[^/]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oClient = $this->getClient(['cookies' => new \GuzzleHttp\Cookie\FileCookieJar(tempnam('/tmp', __CLASS__))]);
        /**
         * @var \GuzzleHttp\HandlerStack $oHandler
         */
        $oHandler = $oClient->getConfig('handler');
        $oHandler->push(CloudflareMiddleware::create());
        $aReturn = [];
        $i = 0;
        $sPageUrl = 'https://www.' . self::DOMAIN . '/Read/Load?q=' . $this->aMatches['album'];
        $oPages = $oClient->request('GET', $sPageUrl);
        $aPages = \GuzzleHttp\json_decode((string)$oPages->getBody(), true);
        foreach ($aPages['reader_page_urls'] as $sToken) {
            $sFilename = 'https://www.' . self::DOMAIN . '/Image/Object?name=' . $sToken;;
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
