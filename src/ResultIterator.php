<?php

namespace Yamete;

/**
 * Class ResultIterator
 * @package Yamete
 * @method \ArrayIterator getInnerIterator
 */
class ResultIterator implements \Iterator, \Countable
{
    private $oDriver;
    private $oIterator;

    public function __construct(DriverInterface $oDriver)
    {
        $this->oDriver = $oDriver;
        $this->oIterator = new \ArrayIterator($oDriver->getDownloadables());
    }

    public function current()
    {
        return new Downloadable($this->oDriver, $this->key(), $this->oIterator->current());
    }

    public function next()
    {
        $this->oIterator->next();
    }

    public function valid()
    {
        return $this->oIterator->valid();
    }

    public function rewind()
    {
        $this->oIterator->rewind();
    }

    public function key()
    {
        return $this->getFilename($this->oIterator->key(), $this->oIterator->current());
    }

    private function getFilename($sFileName, $sResource)
    {
        $sFileName = is_numeric($sFileName) ? basename($sResource) : $sFileName;
        $sFileName = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'downloads', $sFileName]);
        if (!file_exists(dirname($sFileName))) {
            mkdir(dirname($sFileName), 0777, true);
        }
        return $sFileName;
    }

    public function count()
    {
        return count($this->oIterator);
    }
}
