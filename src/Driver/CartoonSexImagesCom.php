<?php

namespace Yamete\Driver;

class CartoonSexImagesCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'cartoonseximages.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-', ]) .
                ')/pictures/(?<album>[^/?]+)[/?]?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $this->sUrl = strpos($this->sUrl, '?') ? substr($this->sUrl, 0, strpos($this->sUrl, '?')) : $this->sUrl;
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $this->sUrl .= ($this->sUrl{strlen($this->sUrl) - 1} != '/') ? '/' : '';
        $aReturn = [];
        $i = 0;
        $sSelector = '.portfolio-normal-width figure a';
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($sSelector) as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $sUrl = 'http://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oImg = $this->getDomParser()->loadFromUrl($sUrl)->find('.main_img img')[0];
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
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
