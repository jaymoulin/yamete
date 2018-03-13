<?php

namespace Yamete\Driver;

class EHentai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'e-hentai.org';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<mode>s|g)/([^/]+)/(?<album>[^/-]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        if ($this->aMatches['mode'] == 's') {
            $sHref = $this->getDomParser()->load((string)$oRes->getBody())->find('#i5 a')[0]->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sHref);
        }
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gdtm a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $oImg = $this->getDomParser()
                ->load((string)$this->getClient()->request('GET', $oLink->getAttribute('href'))->getBody())
                ->find('#i3 img');
            $sFilename = $oImg->getAttribute('src');
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
