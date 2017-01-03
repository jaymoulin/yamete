<?php
namespace SiteDl;

interface DriverInterface
{
    /**
     * Define URL to be parsed
     * @param string $sUrl
     * @return $this
     */
    public function setUrl($sUrl);

    /**
     * Tells if driver can handle defined URL (with setUrl)
     * @uses self::setUrl
     * @return bool
     */
    public function canHandle();

    /**
     * Return URLs that can be downloaded (indexed by optional file name) for specified URL (with setUrl)
     * @uses self::setUrl
     * @return string[]
     */
    public function getDownloadables();
}
