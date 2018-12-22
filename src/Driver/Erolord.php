<?php

namespace Yamete\Driver;

class Erolord extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'erolord.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/[^/]+/(?<album>[0-9]+)/$~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        if (!preg_match('~totalthumbs = (\d+)~', (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $iAlbumId = $this->aMatches['album'];
        for ($i = 1; $i <= $aMatches[1]; $i++) {
            $sUrl = 'http://' . self::DOMAIN . "/view.php?g=$i&d=$iAlbumId";
            /** @var \PHPHtmlParser\Dom\AbstractNode $oImg */
            $oImg = $this->getDomParser()->loadFromUrl($sUrl)->find('.imghref img')[0];
            $sFilename = 'http://' . self::DOMAIN . $oImg->getAttribute('src');
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
