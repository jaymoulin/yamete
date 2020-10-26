<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class HentaiHand extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaihand.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/[^/]{2}/comic/(?<album>[^/]+)/?~',
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
        $sUrl = 'https://' . self::DOMAIN . '/api/comics/' . $this->aMatches['album'] . '/images?nsfw=true';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aInfos = \GuzzleHttp\json_decode((string)$oRes->getBody(), true);
        $aReturn = [];
        $index = 0;
        foreach ($aInfos['images'] as $aData) {
            $sFilename = $aData['source_url'];
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
