<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class SimplyHentai extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'simply-hentai.com';

    public function canHandle(): bool
    {
        $sMatch = '~^https?://(?<domain>[^.]+\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-'])
            . ')/(?<album>[^?]+)(?!all\-pages)~';
        return (bool)preg_match($sMatch, $this->sUrl, $this->aMatches);
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $sUrl = 'https://' . $this->aMatches['domain'] . '/' . $this->aMatches['album'] .
            ($this->aMatches['album']{strlen($this->aMatches['album']) - 1} == '/' ? '' : '/') . 'all-pages';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        $aJson = \GuzzleHttp\json_decode((string)$oRes->getBody(), true);
        foreach ($aJson as $aData) {
            $sFilename = $aData['full'];
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR .
                str_pad($index++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Accept' => 'application/json']]);
    }
}
