<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class AsmHentai extends DriverAbstract
{
    private const DOMAIN = 'asmhentai.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/g/(?<album>[^/?]+)/?~',
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
        $oRes = $this->getClient()
            ->request('GET', 'https://' . self::DOMAIN . '/gallery/' . $this->aMatches['album'] . '/1/');
        $aReturn = [];
        $aMatches = [];
        if (!preg_match('~Page 1 of ([0-9]+)~', (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $iNbPages = (int)$aMatches[1];
        for ($iPage = 1; $iPage <= $iNbPages; $iPage++) {
            $sLink = 'https://' . self::DOMAIN . '/gallery/' . $this->aMatches['album'] . '/' . $iPage . '/';
            $oRes = $this->getClient()->request('GET', $sLink);
            $aMatches = [];
            if (!preg_match_all('~src="([^"]+)"~', (string)$oRes->getBody(), $aMatches)) {
                return [];
            }
            foreach ($aMatches[1] as $sImg) {
                $sFilename = 'https:' . $sImg;
                if (!str_contains($sFilename, 'images.')) {
                    continue;
                }
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
