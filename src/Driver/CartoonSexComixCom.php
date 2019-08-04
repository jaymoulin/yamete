<?php

namespace Yamete\Driver;

class CartoonSexComixCom extends \Yamete\DriverAbstract
{
    const DOMAIN = 'cartoonsexcomix.com';
    private $aMatches = [];

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^(?<scheme>https?)://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
            ')/(pictures|gallery|galleries)/(?<album>[^/?]+)[/?]?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    protected function getSelector(): string
    {
        return '.my-gallery figure a';
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $iParamsPos = strpos($this->sUrl, '?');
        $this->sUrl = $iParamsPos ? substr($this->sUrl, 0, $iParamsPos) : $this->sUrl;
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $this->sUrl .= ($this->sUrl{strlen($this->sUrl) - 1} != '/') ? '/' : '';
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($this->getSelector()) as $oLink) {
            /* @var \PHPHtmlParser\Dom\AbstractNode $oLink */
            $sFilename = $oLink->getAttribute('href');
            $sFilename = strpos($sFilename, 'http') !== false
                ? $sFilename
                : $this->aMatches['scheme'] . '://www.' . $this->getDomain() . $sFilename;
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
