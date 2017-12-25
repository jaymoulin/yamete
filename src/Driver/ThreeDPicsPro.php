<?php

namespace Yamete\Driver;

class ThreeDPicsPro extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '3dpics.pro';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/pics/(?<album>[^/]+)/index\.php$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#thumbTable a') as $oLink) {
            /* @var \DOMElement $oLink */
            $sFilename = str_replace('index.php', $oLink->getAttribute('href'), $this->sUrl);
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
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
