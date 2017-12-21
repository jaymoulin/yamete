<?php

namespace Yamete\Driver;

class AAADream extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'aaadream.com';

    public function canHandle()
    {
        return preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/thread-(?<album>[^-]+)-1-1\.html$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.t_fsz img') as $oImg) {
            /**
             * @var \DOMElement $oImg
             */
            $sFilename = $oImg->getAttribute('file') ?: trim($oImg->getAttribute('src'), '" ');
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
