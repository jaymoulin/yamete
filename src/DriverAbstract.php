<?php

namespace Yamete;

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

abstract class DriverAbstract implements DriverInterface
{
    const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.96 Safari/537.36';
    protected ?string $sUrl = null;
    private ?Client $oClient = null;
    private ?Dom $oDomParser = null;

    public function getClient(array $aOptions = []): Client
    {
        return $this->oClient = $this->oClient ?: new Client($aOptions);
    }

    public function setUrl(string $sUrl): DriverInterface
    {
        $this->sUrl = $sUrl;
        return $this;
    }

    /**
     * Cleans memory
     * @return DriverInterface
     */
    public function clean(): DriverInterface
    {
        $this->oDomParser = null;
        $this->oClient = null;
        return $this;
    }

    protected function getDomParser(): Dom
    {
        return $this->oDomParser = $this->oDomParser ?: new Dom;
    }
}
