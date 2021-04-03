<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class TheYiffGallery extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'theyiffgallery.com';

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
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $sBody = (string)$oRes->getBody();
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->loadStr($sBody)->find('.thumbnails li a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             * @var AbstractNode $oImg
             */
            $sUrl = 'https://' . $this->getDomain() . '/' . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sUrl);
            $sBody = (string)$oRes->getBody();
            $oImg = $this->getDomParser()->loadStr($sBody)->find('#theImage img')[0];
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
