<?php

namespace Yamete\Driver;

class Pururin extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'pururin.io';

    public function canHandle(): bool
    {
        return (bool)preg_match(
                '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/gallery/(?<albumId>[^/]+)/(?<album>.+)~',
                $this->sUrl,
                $this->aMatches
            ) ||
            (bool)preg_match(
                '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/read/(?<albumId>[^/]+)/[0-9]+/(?<album>.+)~',
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
        $sUrl = 'http://' . self::DOMAIN . '/read/' . $this->aMatches['albumId'] . '/01/' . $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', $sUrl);
        if (!preg_match('~<gallery\-read :gallery="([^"]+)"~', (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $aAssets = \GuzzleHttp\json_decode(html_entity_decode($aMatches[1]), true);
        $aReturn = [];
        for ($i = 1; $i <= $aAssets['total_pages']; $i++) {
            $sFilename = "https://api.pururin.io/images/${$this->aMatches['albumId']}/$i.${aAssets['image_extension']}";
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
