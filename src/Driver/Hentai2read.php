<?php

namespace Yamete\Driver;

use \Tuna\CloudflareMiddleware;

class Hentai2read extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    private $iPointer = 0;
    const DOMAIN = 'hentai2read.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @param array $aOptions
     * @return \GuzzleHttp\Client
     */
    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        $oClient = parent::getClient(
            [
                'cookies' => new \GuzzleHttp\Cookie\FileCookieJar(tempnam('/tmp', __CLASS__)),
                'headers' => ['User-Agent' => self::USER_AGENT],
            ]
        );
        /**
         * @var \GuzzleHttp\HandlerStack $oHandler
         */
        $oHandler = $oClient->getConfig('handler');
        $oHandler->push(CloudflareMiddleware::create());
        return $oClient;
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {

        $this->aReturn = [];
        $this->iPointer = 0;
        $this->parse($this->sUrl . '1/');
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function parse(string $sUrl): array
    {
        if (!$sUrl) {
            return $this->aReturn;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        $sAccessor = '~var gData = (?<json>\{[^\}]+\});~';
        if (preg_match($sAccessor, (string)$oRes->getBody(), $aMatches) !== false) {
            $aObj = \GuzzleHttp\json_decode(str_replace('\'', '"', $aMatches['json']), true);
            foreach ($aObj['images'] as $sPostImage) {
                $sFilename = 'https://static.hentaicdn.com/hentai' . $sPostImage;
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$this->iPointer, 5, '0', STR_PAD_LEFT) .
                    '-' . basename($sFilename);
                $this->aReturn[$sPath] = $sFilename;
            }
            $this->parse($aObj['nextURL']);
        }
        return $this->aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
