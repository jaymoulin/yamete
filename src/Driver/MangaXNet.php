<?php

namespace Yamete\Driver;

use GuzzleCloudflare\Middleware;
use GuzzleHttp\Cookie\FileCookieJar;

class MangaXNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangax.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(m|f|c)/(?<album>[^/]+)~',
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
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         * @var \PHPHtmlParser\Dom\AbstractNode[] $oChapters
         */
        $sUrl = 'https://www.' . self::DOMAIN . '/m/' . $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('.chlist li a');
        $index = 0;
        $aReturn = [];
        foreach ($oChapters as $oUrl) {
            $sUrl = 'https://www.' . self::DOMAIN . str_replace('/c/', '/f/', $oUrl->getAttribute('href'));
            $oRes = $this->getClient()->request('GET', $sUrl);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.img-rounded') as $oImg) {
                $sFilename = $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
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
}
