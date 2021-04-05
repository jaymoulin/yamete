<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Traversable;
use Yamete\DriverAbstract;

class PornGamesHDCom extends DriverAbstract
{
    private const DOMAIN = 'porngameshd.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/doujin/(?<album>[^/]+)~',
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
         * @var Traversable $oPages
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $aMatchesCover = [];
        $sBody = (string)$oRes->getBody();
        $aMatches = [];
        if (
            !preg_match_all('~data-original="([^"]+)"~', $sBody, $aMatches) or
            !preg_match_all('~<img class="img-responsive" src="([^"]+)"~', $sBody, $aMatchesCover)
        ) {
            return [];
        }
        $index = 0;
        foreach (array_slice($aMatches[1], 3) as $iKey => $sFilename) {
            $sFilename = str_replace('/smalls/', '/originals/', $sFilename);
            if ($iKey % 2 === 0 or !str_contains($sFilename, '/originals/')) {
                continue;
            }
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        foreach ($aMatchesCover[1] as $sFilename) {
            $sFilename = str_replace('/smalls/', '/originals/', $sFilename);
            if (!str_contains($sFilename, '/originals/')) {
                continue;
            }
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return array_merge(array_slice($aReturn, -1, 1), array_slice($aReturn, 0, $index - 1));
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
