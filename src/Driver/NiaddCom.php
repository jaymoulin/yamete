<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Traversable;
use Yamete\DriverAbstract;

class NiaddCom extends DriverAbstract
{
    private const DOMAIN = 'niadd.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://.{2,3}\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<type>manga|original)/(?<album>[^/.]+)~',
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
        /**
         * @var Traversable $oChapters
         */

        $sUrl = 'https://es.' . self::DOMAIN . "/{$this->aMatches['type']}/{$this->aMatches['album']}/chapters.html?warning=1";
        $oResult = $this->getClient()->request('GET', $sUrl);
        $aMatches = [];
        if (!preg_match_all('~<a class="hover-underline" href="([^"]+)"~', (string)$oResult->getBody(), $aMatches)) {
            return [];
        }
        krsort($aMatches[1]);
        $aReturn = [];
        $index = 0;
        foreach ($aMatches[1] as $sLink) {
            $oResult = $this->getClient()->request('GET', $sLink);
            $aMatches = [];
            $sRegexp = '~<div class="option-item-trigger chp-page-trigger chp-selection-item" option_val="([^"]+)"~';
            if (!preg_match_all($sRegexp, (string)$oResult->getBody(), $aMatches)) {
                return [];
            }
            $aFound = [];
            foreach ($aMatches[1] as $sPage) {
                $oResult = $this->getClient()->request('GET', $sPage);
                $aMatches = [];
                $sRegexp = '~<img class="manga_pic [^"]+" .+src="([^"]+)"~';
                if (!preg_match($sRegexp, (string)$oResult->getBody(), $aMatches)) {
                    return [];
                }
                $sFilename = $aMatches[1];
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                if (isset($aFound[$sFilename])) {
                    break;
                }
                $aFound[$sFilename] = true;
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
