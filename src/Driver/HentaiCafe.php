<?php

namespace Yamete\Driver;

class HentaiCafe extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai.cafe';

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $sBaseUrl = 'https://' . $this->getDomain() . '/manga/read/' . $this->aMatches['album'] . '/en/0/1/page/';
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
