<?php

namespace Yamete\Driver;

use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Client;

class GomangaXyz extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'gomanga.xyz';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $sRegExp = '~<div class="col-xs-12 chapter">[^<]+<h4>[^<]+<a  href="([^"]+)"~';
        $aMatches = [];
        if (!preg_match_all($sRegExp, (string)$oResult->getBody(), $aMatches)) {
            return [];
        }
        $aChapters = $aMatches[1];
        krsort($aChapters);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters as $sLink) {
            $oResult = $this->getClient()->request('GET', $sLink);
            $sRegExp = '~<p id=arraydata style=display:none>([^<]+)</p>~';
            $aMatches = [];
            if (!preg_match($sRegExp, (string)$oResult->getBody(), $aMatches)) {
                continue;
            }
            foreach (explode(',', $aMatches[1]) as $sFilename) {
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
