<?php

namespace Yamete\Driver;

class TnaFlixCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'tnaflix.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/([a-z]{2}/)?gallery/(?<album>[^/?]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $sUrl = "https://www." . self::DOMAIN . '/gallery/' . $this->aMatches['album'];
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $aMatch = [];
        $index = 0;
        $sRegExp = '~<span class="text\-gray">(?<nbElem>[0-9]+) photos?</span></h2>~';
        if (!preg_match($sRegExp, (string)$oRes->getBody(), $aMatch)) {
            return $aReturn;
        }
        $iNbElem = (int)$aMatch['nbElem'];
        $iNbPage = floor($iNbElem / 60) + 1;
        for ($iPage = 1; $iPage <= $iNbPage; $iPage++) {
            $sPageUrl = $sUrl . '?page=' . $iPage;
            $oRes = $this->getClient()->request('GET', $sPageUrl);
            $aMatches = [];
            if (!preg_match_all('~<li class="igItem .+?data-src="(?<url>[^"]+)"~', $oRes->getBody(), $aMatches)) {
                continue;
            }
            foreach ($aMatches['url'] as $sFilename) {
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
