<?php

namespace Yamete\Driver;

class PorncomixSite extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'porncomix.site';

    public function canHandle()
    {
        return preg_match(
            '~^http://' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.post-content figure a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $sFilename = $oLink->getAttribute('href');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
