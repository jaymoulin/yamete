<?php

namespace Yamete\Driver;

class NudeMoon extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'nude-moon.me';

    public function canHandle(): bool
    {
        $sMatch = '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-'])
            . ')/(?<albumId>[0-9]+)-online-(?<album>.+)\.html$~';
        return (bool)preg_match($sMatch, $this->sUrl, $this->aMatches);
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        if (preg_match_all("~images\[[0-9]+\]\.src = '(?<url>[^']+)';~", (string)$oRes->getBody(), $aMatches)) {
            foreach ($aMatches['url'] as $sUrl) {
                $sFilename = str_replace('./', 'http://' . self::DOMAIN . '/', $sUrl);
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
