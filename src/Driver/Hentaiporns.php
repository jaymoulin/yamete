<?php

namespace Yamete\Driver;

class Hentaiporns extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaiporns.net';

    public function canHandle()
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gallery-icon a') as $oImg) {
            /**
             * @var \DOMElement $oImg
             */
            $sFilename = $oImg->getAttribute('href');
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR .
                str_pad(++$i, 5, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
