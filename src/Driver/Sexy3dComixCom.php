<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class Sexy3dComixCom extends DriverAbstract
{
    const DOMAIN = 'sexy3dcomix.com';
    private $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^(?<scheme>https?)://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
            ')/(pictures|gallery|galleries|videos)/(?<album>[^/?]+)[/?]?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    protected function getSelector(): string
    {
        return '.gallery-thumbs figure a';
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
            $sLink = "http://" . $this->getDomain() . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#main_img a')[0];
            $sFilename = $oImg->getAttribute('href');
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

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Referer' => $this->sUrl]]);
    }
}
