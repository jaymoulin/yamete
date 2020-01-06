<?php

namespace Yamete\Driver;

use GuzzleCloudflare\Middleware;
use GuzzleHttp\Cookie\FileCookieJar;

class BaraMangaOnlineCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'baramangaonline.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)~',
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
        /**
         * @var \PHPHtmlParser\Dom\AbstractNode $oUrl
         */
        $sUrl = 'https://' . self::DOMAIN . '/' . $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oImgs = $this->getDomParser()->load((string)$oRes->getBody())->find('.entry-inner > div > a');
        $index = 0;
        $aReturn = [];
        foreach ($oImgs as $oUrl) {
            $sFileToDown = 'https://' . self::DOMAIN . $oUrl->getAttribute('href');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFileToDown);
            $aReturn[$sBasename] = $sFileToDown;
        }
        return $aReturn;
    }

    /**
     * @param array $aOptions
     * @return \GuzzleHttp\Client
     */
    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        $oClient = parent::getClient(
            [
                'cookies' => new FileCookieJar(tempnam('/tmp', __CLASS__)),
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
        /**
         * @var \GuzzleHttp\HandlerStack $oHandler
         */
        $oHandler = $oClient->getConfig('handler');
        $oHandler->remove('cloudflare');
        $oHandler->push(Middleware::create(), 'cloudflare');
        return $oClient;
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
