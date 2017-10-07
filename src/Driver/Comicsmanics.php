<?php

namespace Yamete\Driver;

class Comicsmanics extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'comicsmanics.com';

    public function canHandle()
    {
        return preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.single-post img.size-full') as $oImg) {
            /**
             * @var \DOMElement $oImg
             */
            $sFilename = 'https://www.' . self::DOMAIN . $oImg->getAttribute('src');
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
