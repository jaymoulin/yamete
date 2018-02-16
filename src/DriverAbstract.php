<?php

namespace Yamete;

abstract class DriverAbstract implements DriverInterface
{
    const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.167 Safari/537.36';
    private $oClient;
    private $oDomParser;
    protected $sUrl;

    protected function getClient($aOptions = [])
    {
        return $this->oClient = $this->oClient ?: new \GuzzleHttp\Client($aOptions);
    }

    protected function getDomParser()
    {
        return $this->oDomParser = $this->oDomParser ?: new \PHPHtmlParser\Dom;
    }

    public function setUrl($sUrl)
    {
        $this->sUrl = (string)$sUrl;
        return $this;
    }
}
