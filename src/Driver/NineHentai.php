<?php

namespace Yamete\Driver;

class NineHentai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = '9hentai.com';

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $aReturn = [];
        $oRes = $this->getClient()->request('POST', 'https://9hentai.com/api/getBookByID', [
            'form_params' => [
                "id" => $this->aMatches['album'],
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
     * @return \GuzzleHttp\Client
     */
    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        return parent::getClient(['headers' => ['Content-Type' => 'application/json'],]);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
