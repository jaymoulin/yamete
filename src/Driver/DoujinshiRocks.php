<?php

namespace Yamete\Driver;

class DoujinshiRocks extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'doujinshi.rocks';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            '/(?<album>[^/]+)/?~',
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
        $aReturn = [];
        if (preg_match('~var js_array = \[([^\]]+)\];~', (string)$oRes->getBody(), $aMatches)) {
            foreach (\GuzzleHttp\json_decode('[' . $aMatches[1] . ']') as $sFilename) {
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
