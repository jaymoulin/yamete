<?php

namespace Yamete\Driver;

class CartoonPornPicNet extends \Yamete\DriverAbstract
{
    const DOMAIN = 'cartoonpornpic.net';
    private $aMatches = [];

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
            ')/(pictures|gallery|galleries|images)/(?<album>[^/?]+)[/?]?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    protected function getSelector()
    {
        return '#wall figure a';
    }

    public function getDownloadables()
    {
        $this->sUrl = strpos($this->sUrl, '?') ? substr($this->sUrl, 0, strpos($this->sUrl, '?')) : $this->sUrl;
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $this->sUrl .= ($this->sUrl{strlen($this->sUrl) - 1} != '/') ? '/' : '';
        $aReturn = [];
        $iNbPage = count($this->getDomParser()->load((string)$oRes->getBody())->find($this->getSelector()));
        for ($i = 1; $i <= $iNbPage; $i++) {
            /* @var \DOMElement $oImg */
            $oImg = $this->getDomParser()->loadFromUrl($this->sUrl . $i)->find('.main_img img')[0]
                ?: $this->getDomParser()->loadFromUrl($this->sUrl . $i)->find('.main-img img')[0];
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
