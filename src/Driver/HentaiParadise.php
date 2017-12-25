<?php

namespace Yamete\Driver;

class HentaiParadise extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai-paradise.fr';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/doujins/(?<album>[^?/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl . '/0');
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.goPage a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $oImg = $this->getDomParser()
                ->load(
                    (string)$this->getClient()->request('GET', $this->sUrl . '/' . $oLink->getAttribute('href'))
                        ->getBody()
                )
                ->find('#fullPage img');
            $sFilename = $this->sUrl . '/' . $oImg->getAttribute('src');
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
