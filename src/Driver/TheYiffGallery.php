<?php

namespace Yamete\Driver;

class TheYiffGallery extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'theyiffgallery.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/index\?/category/(?<album>[0-9]+)$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $sBody = (string)$oRes->getBody();
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load($sBody)->find('.thumbnails li a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sUrl = 'https://' . $this->getDomain() . '/' . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sUrl);
            $sBody = (string)$oRes->getBody();
            $oImg = $this->getDomParser()->load($sBody)->find('#theImage img')[0];
            $sFilename = 'https://' . $this->getDomain() . '/' . $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
