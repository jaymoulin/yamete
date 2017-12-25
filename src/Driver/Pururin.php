<?php

namespace Yamete\Driver;

class Pururin extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'pururin.us';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/gallery/(?<albumId>[^/]+)/(?<album>.+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $sUrl = 'http://' . self::DOMAIN . '/read/' . $this->aMatches['albumId'] . '/01/' . $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', $sUrl);
        if (!preg_match('~var chapters = ([^;]+);~', (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $aAssets = \GuzzleHttp\json_decode(trim($aMatches[1]), true);
        $aReturn = [];
        foreach ($aAssets as $aData) {
            $sFilename = $aData['image'];
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($aData['page'], 5, '0', STR_PAD_LEFT)
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
