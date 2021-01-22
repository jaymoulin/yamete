<?php

namespace Yamete;

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

abstract class DriverAbstract implements DriverInterface
{
    const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36';
    private $oClient;
    private $oDomParser;
    protected $sUrl;

    public function getClient(array $aOptions = []): Client
    {
        return $this->oClient = $this->oClient ?: new Client($aOptions);
    }

    protected function getDomParser(): Dom
    {
        return $this->oDomParser = $this->oDomParser ?: new Dom;
    }

    public function setUrl(string $sUrl): DriverInterface
    {
        $this->sUrl = (string)$sUrl;
        return $this;
    }

    /**
     * Cleans memory
     * @return DriverInterface
     */
    public function clean(): DriverInterface
    {
        unset($this->oDomParser);
        unset($this->oClient);
        return $this;
    }
}
