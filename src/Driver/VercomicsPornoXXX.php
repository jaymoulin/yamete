<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class VercomicsPornoXXX extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'vercomicsporno.xxx';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $sContent = file_get_contents($this->sUrl);
        $aReturn = [];
        $index = 0;
        $aMatches = [];
        if (!preg_match_all('~src=([^ ]+) alt></figure~', $sContent, $aMatches)) {
            return [];
        }
        foreach ($aMatches[1] as $sFilename) {
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
