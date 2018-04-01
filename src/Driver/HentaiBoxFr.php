<?php

namespace Yamete\Driver;

class HentaiBoxFr extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaibox.fr';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables()
    {
        $aReturn = [];
        for ($i = 1; $i <= 999; $i++) {
            $this->sUrl = 'http://' . self::DOMAIN . '/manga/' . $this->aMatches['album'] . '/lire/page/' . $i;
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            /* @var \DOMElement $oImg */
            $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('.span11 a img')[0];
            if (!$oImg) {
                return $aReturn;
            }
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i, 3, '0', STR_PAD_LEFT)
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
