<?php

namespace Yamete\Driver;


class HentaiFox extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaifox.com';

    public function canHandle()
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/gallery/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.preview_thumb a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . '/' . $oLink->getAttribute('href'));
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#gimg') as $oImg) {
                /**
                 * @var \DOMElement $oImg
                 */
                $sFilename = 'https:' . $oImg->getAttribute('src');
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
