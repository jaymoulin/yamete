<?php

namespace Yamete\Driver;

class EightMusesDownload extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '8muses.download';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.popup-gallery figure a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $sFilename = $oLink->getAttribute('href');
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                . str_pad($i++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
