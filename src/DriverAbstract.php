<?php

namespace Yamete;

abstract class DriverAbstract implements DriverInterface
{
    const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36'
        . ' (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36';
    private $oClient;
    private $oDomParser;
    protected $sUrl;

    public function getClient(array $aOptions = []) : \GuzzleHttp\Client
    {
        return $this->oClient = $this->oClient ?: new \GuzzleHttp\Client($aOptions);
    }

    protected function getDomParser() : \PHPHtmlParser\Dom
    {
        return $this->oDomParser = $this->oDomParser ?: new \PHPHtmlParser\Dom;
    }

    public function setUrl(string $sUrl) : DriverInterface
    {
        $this->sUrl = (string)$sUrl;
        return $this;
    }
}
