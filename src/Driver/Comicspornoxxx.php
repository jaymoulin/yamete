<?php

namespace Yamete\Driver;

class Comicspornoxxx extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'comicspornoxxx.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        /**
         * @var \DOMElement $oLink
         */
        $oLink = $this->getDomParser()->load((string)$oRes->getBody())->find('.entry-content .su-button-center a')[0];
        $sUrl = $oLink->getAttribute('href');
        $oRes = $this->getClient()->request('GET', $sUrl);
        preg_match('~^https?://(?<domain>[^/]+)/~', $sUrl, $aDomains);
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.thumbnail a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $sLink = 'https://' . $aDomains['domain'] . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#center .text-center img') as $oImg) {
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = 'https://' . $aDomains['domain'] . $oImg->getAttribute('src');
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
