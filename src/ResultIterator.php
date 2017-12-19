<?php

namespace Yamete;


class ResultIterator extends \IteratorIterator
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
            mkdir(dirname($sFileName), 0644, true);
        }
        return $sFileName;
    }

}
