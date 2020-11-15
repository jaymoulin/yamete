<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class NineHentai extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '9hentai.ru';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/g/(?<album>[0-9]+)/~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $sBody = (string)$oRes->getBody();
        $aMatches= [];
        if (preg_match('~name="csrf-token" content="(?<csrf>[^"]+)~', $sBody, $aMatches) === false) {
            return [];
        }
        $aReturn = [];
        $oRes = $this->getClient()->request('POST', 'https://9hentai.ru/api/getBookByID', [
            'json' => [
                "id" => $this->aMatches['album'],
            ],
            'headers' => [
                'x-csrf-token' => $aMatches['csrf'],
            ]
        ]);
        $aJson = \GuzzleHttp\json_decode((string)$oRes->getBody(), true);
        if ($aJson['status'] !== true) {
            return [];
        }
        for ($index = 1; $index <= $aJson['results']['total_page']; $index++) {
            $sFilename = $aJson['results']['image_server'] . $this->aMatches['album'] . '/' . $index . '.jpg';
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    /**
     * @param array $aOptions
     * @return Client
     */
    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Content-Type' => 'application/json'],]);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
