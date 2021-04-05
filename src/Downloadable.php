<?php

namespace Yamete;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Downloadable
{
    private DriverInterface $oDriver;
    private string $sPath;
    private string $sUrl;

    public function __construct(DriverInterface $oDriver, string $sPath, string $sUrl)
    {
        $this->oDriver = $oDriver;
        $this->sPath = $sPath;
        $this->sUrl = $sUrl;
    }

    public function getPath(): string
    {
        return $this->sPath;
    }

    public function getUrl(): string
    {
        return $this->sUrl;
    }

    /**
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function download(): ResponseInterface
    {
        return $this->oDriver->getClient()
            ->request('GET', $this->sUrl, ['sink' => $this->sPath, 'http_errors' => false]);
    }
}
