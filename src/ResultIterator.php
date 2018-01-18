<?php

namespace Yamete;

/**
 * Class ResultIterator
 * @package Yamete
 * @method \ArrayIterator getInnerIterator
 */
class ResultIterator extends \IteratorIterator implements \Countable
{
    public function key()
    {
        return $this->getFilename(parent::key(), parent::current());
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
        return count($this->getInnerIterator());
    }
}
