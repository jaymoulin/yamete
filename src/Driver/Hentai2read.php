<?php

namespace Yamete\Driver;

use \Tuna\CloudflareMiddleware;

class Hentai2read extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai2read.com';

    public function canHandle()
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
    public function getClient($aOptions = [])
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
    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl . '1/');
        $aReturn = [];
        $i = 0;
        $sAccessor = '.pageDropdown .scrollable-dropdown li a';
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($sAccessor) as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            if (empty($oLink->getAttribute('data-pagid'))) {
                continue;
            }
            $oRes = $this->getClient()->request('GET', $this->sUrl . '1/' . $oLink->getAttribute('data-pagid') . '/');
            $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#arf-reader')[0];
            $sFilename = $oImg->getAttribute('src');
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT) . '-'
                . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
