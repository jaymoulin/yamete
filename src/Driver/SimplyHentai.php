<?php

namespace Yamete\Driver;

class SimplyHentai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'simply-hentai.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(?<domain>[^.]+\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^?]+))~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', 'http://' . $this->aMatches['domain'] . '/all-pages');
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a.image-preview') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $oImg = $this->getDomParser()
                ->load((string)$this->getClient()->request('GET', $oLink->getAttribute('href'))->getBody())
                ->find('.next-link picture img');
            $sFilename = $oImg->getAttribute('src');
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR .
                str_pad($i++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
