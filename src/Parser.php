<?php

namespace Yamete;

use \UnexpectedValueException;
use \crodas\ClassInfo\ClassInfo;
use \RuntimeException;

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
    public function addDriverDirectory(string $sDirectory): Parser
    {
        substr($sDirectory, -1) != '/' && $sDirectory .= '/';
        if (!is_dir($sDirectory)) {
            throw new UnexpectedValueException($sDirectory . ' is not a valid directory');
        }
        foreach (glob($sDirectory . '*.php') as $sDriver) {
            $aClasses = (new ClassInfo($sDriver))->getClasses();
            if (count($aClasses)) {
                $sClass = (string)current($aClasses);
                include $sDriver;
                $oDriver = new $sClass;
                if (!$oDriver instanceof DriverInterface) {
                    throw new RuntimeException("Driver $sClass ($sDriver) must implements " . DriverInterface::class);
                }
                $this->aDrivers[] = $oDriver;
            }
        }
        return $this;
    }

    /**
     * @param string $sUrl Url to parse
     * @return bool|ResultIterator
     */
    public function parse(string $sUrl)
    {
        foreach ($this->aDrivers as $oDriver) {
            $oDriver->setUrl($sUrl);
            if ($oDriver->canHandle()) {
                return new ResultIterator($oDriver);
            }
        }
        return false;
    }
}
