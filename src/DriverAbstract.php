<?php

namespace SiteDl;

abstract class DriverAbstract implements DriverInterface
{
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
