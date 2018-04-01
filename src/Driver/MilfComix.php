<?php

namespace Yamete\Driver;

class MilfComix extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'milfcomix.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl, ['headers' => ['User-Agent' => self::USER_AGENT]]);
        $aReturn = [];
        $i = 0;
        $sBody = (string)$oRes->getBody();
        $sRegexp = '~<img class="alignnone[^"]+" src="(?<href>[^"]+)"[^>]+>~';
        if (!preg_match_all($sRegexp, $sBody, $aMatches)) {
            return $aReturn;
        }
        foreach ($aMatches['href'] as $sFilename) {
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$i, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
