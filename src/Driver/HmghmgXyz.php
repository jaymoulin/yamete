<?php

namespace Yamete\Driver;

class HmghmgXyz extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hmghmg.xyz';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            '/(?<lang>[^/]+)/(?<server>[^/]+)/(?<album>[^/]+)/~',
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
        $sRegExp = '~data-original="([^"]+)"~';
        $aReturn = [];
        $aMatches = [];
        if (!preg_match_all($sRegExp, $sBody, $aMatches)) {
            return [];
        }
        foreach ($aMatches[1] as $sFilename) {
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
