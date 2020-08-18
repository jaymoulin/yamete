<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class MyHentaiGallery extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'myhentaigallery.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-'])
            . '/gallery/thumbnails/(?<album>[0-9]+)$~',
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
        $aReturn = [];
        $index = 0;
        $sRegExp = '~<img src="([^"]+)~us';
        $aMatches = [];
        if (!preg_match_all($sRegExp, (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        foreach (array_splice($aMatches[1], 2) as $sImg) {
            $sFilename = html_entity_decode(
                str_replace('/thumbnail/', '/original/', $sImg),
                ENT_QUOTES
            );
            $iPos = strpos($sFilename, '?');
            if ($iPos) {
                $sFilename = substr($sFilename, 0, $iPos);
            }
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
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
