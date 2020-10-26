<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class HentaiCafe extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hentai.cafe';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/(?<album>[^/]+)/$~',
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
        $sRegExp = '~href="([^"]+)" title="Read"~';
        $aMatches = [];
        if (!preg_match($sRegExp, (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $sBaseUrl = $aMatches[1] . 'page/';
        $oRes = $this->getClient()->request('GET', $sBaseUrl . 1);
        $sRegExp = '~var pages = ([^;]+);~';
        if (!preg_match($sRegExp, (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $aObjets = \GuzzleHttp\json_decode($aMatches[1], true);
        $index = 0;
        $aReturn = [];
        foreach ($aObjets as $aResult) {
            $sFilename = $aResult['url'];
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
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
