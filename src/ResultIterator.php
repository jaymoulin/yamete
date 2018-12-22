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

    public function current(): Downloadable
    {
        return new Downloadable($this->oDriver, $this->key(), $this->oIterator->current());
    }

    public function next(): void
    {
        $this->oIterator->next();
    }

    public function valid(): bool
    {
        return $this->oIterator->valid();
    }

    public function rewind(): void
    {
        $this->oIterator->rewind();
    }

    public function key(): string
    {
        return $this->getFilename($this->oIterator->key(), $this->oIterator->current());
    }

    private function getFilename(string $sFileName, string $sResource): string
    {
        $sFileName = is_numeric($sFileName) ? basename($sResource) : $sFileName;
        $sFileName = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'downloads', $sFileName]);
        if (!file_exists(dirname($sFileName))) {
            mkdir(dirname($sFileName), 0777, true);
        }
        return $sFileName;
    }

    public function count(): int
    {
        return count($this->oIterator);
    }
}
