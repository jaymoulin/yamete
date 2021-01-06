<?php

namespace Yamete\Driver;

use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class NHentai extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'nhentai.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<lang>[a-z]{2})/(?<categ>[^/]+)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oClient = $this->getClient(['cookies' => new FileCookieJar(tempnam('/tmp', __CLASS__))]);
        /**
         * @var HandlerStack $oHandler
         */
        $oHandler = $oClient->getConfig('handler');
        $oHandler->push(GuzzleRetryMiddleware::factory());
        $this->sUrl = 'https://' . implode('/', [self::DOMAIN, 'api/comics', $this->aMatches['album'], 'images?nsfw=true']);
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        $aJson = \GuzzleHttp\json_decode((string)$oRes->getBody(), true);
        foreach ($aJson['images'] as $aImg) {
            /** @var [] $aImg */
            $sFilename = $aImg['source_url'];
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                . str_pad($index++, 5, '0', STR_PAD_LEFT) . '-'
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
