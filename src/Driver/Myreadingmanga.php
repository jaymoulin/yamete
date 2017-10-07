<?php

namespace Yamete\Driver;

class Myreadingmanga extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $iCurrentPage = 0;
    const DOMAIN = 'myreadingmanga.info';

    public function canHandle()
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    private function getImgListForBody($sBody)
    {
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$sBody)->find('.content .separator img') as $oImg) {
            /** @var \DOMElement $oImg */
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$this->iCurrentPage, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oPageList = $this->getDomParser()->load((string)$oRes->getBody())->find('.pagination a');
        $aReturn = $this->getImgListForBody((string)$oRes->getBody());
        foreach ($oPageList as $oLink) {
            /** @var \DOMElement $oLink */
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $aReturn = array_merge($aReturn, $this->getImgListForBody((string)$oRes->getBody()));
        }
        return $aReturn;
    }


    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
