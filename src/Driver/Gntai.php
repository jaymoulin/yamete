<?php

namespace Yamete\Driver;

class Gntai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'gntai.xyz';

    private function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            '/(?<year>[0-9]+)/(?<month>[0-9]+)/(?<album>[^.]+).html~',
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
        $sBody = (string)$oRes->getBody();
        $aReturn = [];
        $sRegExp = '~<script>pages=(?<json>[^;]+);~';
        if (preg_match($sRegExp, $sBody, $aMatch)) {
            $aList = \GuzzleHttp\json_decode($aMatch['json'], true);
            foreach ($aList as $sFilename) {
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
