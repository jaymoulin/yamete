<?php

namespace Yamete\Driver;

class ThreeDSexToonsNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '3dsextoons.net';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/gals/(?<album>[^/]+)/(?<albumId>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $sUrl = str_replace(self::DOMAIN, 'page-x.com', $this->sUrl);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $iNbImg = count($this->getDomParser()->load((string)$oRes->getBody())->find('#gallery2 a'));
        for ($i = 1; $i <= $iNbImg; $i++) {
            $sFilename = $sUrl . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg';
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
