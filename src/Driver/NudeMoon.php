<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class NudeMoon extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'nude-moon.net';

    public function canHandle(): bool
    {
        $sMatch = '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-'])
            . ')/(?<albumId>[0-9]+)-online-(?<album>.+)\.html$~';
        return (bool)preg_match($sMatch, $this->sUrl, $this->aMatches);
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $aMatches = [];
        if (!preg_match_all("~images\[[0-9]+\]\.src = '(?<url>[^']+)';~", (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        foreach ($aMatches['url'] as $sUrl) {
            $sFilename = str_replace('./', 'https://' . self::DOMAIN . '/', $sUrl);
            $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
