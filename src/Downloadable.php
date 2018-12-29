<?php

namespace Yamete;

class Downloadable
{
    private $oDriver;
    private $sPath;
    private $sUrl;

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
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function download(): \Psr\Http\Message\ResponseInterface
    {
        return $this->oDriver->getClient()->request('GET', $this->sUrl, ['sink' => $this->sPath, 'http_errors' => false]);
    }
}
