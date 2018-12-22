<?php

namespace Yamete;

interface DriverInterface
{
    /**
     * Defines URL to be parsed
     * @param string $sUrl
     * @return $this
     */
    public function setUrl(string $sUrl) : DriverInterface;

    /**
     * Tells if driver can handle defined URL (with setUrl)
     * @uses self::setUrl
     * @return bool
     */
    public function canHandle() : bool;

    /**
     * Returns URLs that can be downloaded (indexed by optional file name) for specified URL (with setUrl)
     * @uses self::setUrl
     * @return string[]
     */
    public function getDownloadables() : array;

    /**
     * Returns Guzzle client that will be used to download resources
     * @return \GuzzleHttp\Client
     */
    public function getClient() : \GuzzleHttp\Client;
}
