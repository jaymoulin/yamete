<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

class ComixzillaCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'comixzilla.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<lang>[a-z]{2})/comic\-g/(?<album>[^/]+)/$~',
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
        $aMatches = [];
        $sReg = '~"items":\[([^\]]+)\]~um';
        $index = 0;
        if (!preg_match($sReg, (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $aJson = \GuzzleHttp\json_decode('[' . $aMatches[1] . ']', true);
        foreach ($aJson as $aItem) {
            $sFilename =$aItem['link'];
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(++$index, 5, '0', STR_PAD_LEFT)
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
