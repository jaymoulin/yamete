<?php

namespace Yamete\Driver;

class EightMuses extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '8muses.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/comix/album/(?<album>[^?]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a.c-tile') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $oParser = $this->getDomParser()
                ->load(
                    (string)$this->getClient()
                        ->request('GET', 'https://www.' . self::DOMAIN . $oLink->getAttribute('href'))->getBody()
                );
            $sHost = $oParser->find('#imageHost')[0]->getAttribute('value');
            $sName = $oParser->find('#imageName')[0]->getAttribute('value');
            $sFilename = "https:$sHost/image/fl/$sName";
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
