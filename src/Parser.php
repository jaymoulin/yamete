<?php

namespace SiteDl;

class Parser
{
    /**
     * @var DriverInterface[]
     */
    private $aDrivers = [];

    public function __construct()
    {
        $this->addDriverDirectory(__DIR__ . DIRECTORY_SEPARATOR . 'Driver');
    }

    /**
     * Add a directory with drivers
     * @param string $sDirectory
     * @return $this
     */
    public function addDriverDirectory($sDirectory)
    {
        substr($sDirectory, -1) != '/' && $sDirectory .= '/';
        if (!is_dir($sDirectory)) {
            throw new \UnexpectedValueException($sDirectory . ' is not a valid directory');
        }
        foreach (glob($sDirectory . '*.php') as $sDriver) {
            $aClasses = (new \crodas\ClassInfo\ClassInfo($sDriver))->getClasses();
            if (count($aClasses)) {
                $sClass = (string)current($aClasses);
                include $sDriver;
                $oDriver = new $sClass;
                if (!$oDriver instanceof DriverInterface) {
                    throw new \RuntimeException("Driver $sClass ($sDriver) must implements " . DriverInterface::class);
                }
                $this->aDrivers[] = $oDriver;
            }
        }
        return $this;
    }

    /**
     * @param string $sUrl Url to parse
     * @return bool|string[]
     */
    public function parse($sUrl)
    {
        foreach ($this->aDrivers as $oDriver) {
            $oDriver->setUrl($sUrl);
            if ($oDriver->canHandle()) {
                return $oDriver->getDownloadables();
            }
        }
        return false;
    }
}
