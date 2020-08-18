<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class CartoonSexComixCom extends DriverAbstract
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
            ')/(pictures|gallery|galleries|videos)/(?<album>[^/?]+)[/?]?~',
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
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $iParamsPos = strpos($this->sUrl, '?');
        $this->sUrl = $iParamsPos ? substr($this->sUrl, 0, $iParamsPos) : $this->sUrl;
        $oRes = $this->getClient()->request('GET', $this->sUrl, ['http_errors' => false]);
        $this->sUrl .= ($this->sUrl{strlen($this->sUrl) - 1} != '/') ? '/' : '';
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($this->getSelector()) as $oLink) {
            /* @var AbstractNode $oLink */
            $sFilename = $oLink->getAttribute('href');
            $sFilename = strpos($sFilename, 'http') !== false
                ? $sFilename
                : (
                strpos($sFilename, '//') !== false
                    ? $this->aMatches['scheme'] . ':' . $sFilename
                    : $this->aMatches['scheme'] . '://www.' . $this->getDomain() . $sFilename);
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
