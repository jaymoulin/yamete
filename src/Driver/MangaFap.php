<?php

namespace Yamete\Driver;

use \GuzzleHttp\RequestOptions;

class MangaFap extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangafap.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/read-(?<album>[^/]+)/$~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $sRegExpId = '~http://mangafap\.com/\?p=(?<id>[0-9]+)\'~';
        $sBody = (string)$oRes->getBody();
        if (!preg_match($sRegExpId, $sBody, $aMatches)) {
            return [];
        }
        $sId = (int)$aMatches['id'];
        $sRegExpNbPage = '~Total No of Images in Gallery: (?<totalPages>[0-9]+)~';
        if (!preg_match($sRegExpNbPage, $sBody, $aMatches)) {
            return [];
        }
        $iTotalPage = (int)$aMatches['totalPages'];
        $aReturn = [];
        $bShouldReplace = false;
        for ($i = 1; $i <= $iTotalPage; $i++) {
            $sFilename = 'http://' . $this->getDomain() . '/images/'. $sId . '/' . $i . '.jpg';
            if ($i == 1) {
                $oRes = $this->getClient()->request('GET', $sFilename, [RequestOptions::ALLOW_REDIRECTS => false]);
                $bShouldReplace = $oRes->getStatusCode() != 200;
            }
            $sFilename = str_replace('/images/', $bShouldReplace ? '/images2/' : '/images/', $sFilename);
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
