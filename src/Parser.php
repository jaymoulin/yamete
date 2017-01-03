<?php

namespace SiteDl;

class Parser
{
    /**
     * Add a directory with drivers
     * @param string $sDirectory
     * @return $this
     */
    public function addDriverDirectory($sDirectory)
    {
        return $this;
    }

    /**
     * @param string $sUrl Url to parse
     * @return bool
     */
    public function parse($sUrl)
    {
        return true;
    }
}
