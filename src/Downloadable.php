<?php
namespace Yamete;

class Downloadable
{
    private $oDriver;
    private $sPath;
    private $sUrl;

    public function __construct(DriverInterface $oDriver, $sPath, $sUrl)
    {
        $this->oDriver = $oDriver;
        $this->sPath = $sPath;
        $this->sUrl = $sUrl;
    }

    public function getPath()
    {
        return $this->sPath;
    }

    public function getUrl()
    {
        return $this->sUrl;
    }

    public function download()
    {
        return $this->oDriver->getClient()->request('GET', $this->sUrl, ['sink' => $this->sPath]);
    }
}
