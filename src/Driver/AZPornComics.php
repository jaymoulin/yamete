<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class AZPornComics extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'azporncomics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            '/book/(?<album>[^/]+)/~',
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
        $aReturn = [];
        $aMatches = [];
        $sRegExp = '~href="([^"]+)"~';
        $bIsFirst = true;
        if (!preg_match_all($sRegExp, $sBody, $aMatches)) {
            return [];
        }
        foreach ($aMatches[1] as $sFilename) {
            if (preg_match('~\.jpe?g$~', $sFilename)) {
                if ($bIsFirst) {
                    $bIsFirst = false;
                    continue;
                }
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
