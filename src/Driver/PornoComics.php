<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class PornoComics extends DriverAbstract
{
    private const DOMAIN = 'pornocomics.net';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>.+).html$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $aImgs = [];
        $index = 0;
        $sRegExp = '~<img class="alignnone" .+?src="(?<filename>[^"]+)">~';
        if (!preg_match_all($sRegExp, (string)$oRes->getBody(), $aImgs)) {
            return [];
        }
        foreach ($aImgs['filename'] as $sFilename) {
            if (str_contains($sFilename, '.js')) {
                continue;
            }
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
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
