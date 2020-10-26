<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class Perveden extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'perveden.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) .
            ')/(?<locale>[^/]+)/(?<category>[^/]+)/(?<album>[^/]+)/~',
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
        $this->sUrl = 'https://www.' . implode('/', [
                self::DOMAIN, $this->aMatches['locale'], $this->aMatches['category'], $this->aMatches['album'], 1, 1
            ]) . '/';
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $aJson = [];
        $index = 0;
        if (!preg_match('~var pages = ([^;]+);~s', (string)$oRes->getBody(), $aJson)) {
            return [];
        }
        foreach (\GuzzleHttp\json_decode($aJson[1], true) as $aEntity) {
            $sFilename = "https:" . array_pop($aEntity['resized_images']);
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
