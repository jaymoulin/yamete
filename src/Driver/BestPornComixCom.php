<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class BestPornComixCom extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'bestporncomix.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/gallery/(?<album>[^/]+)~',
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
        $sUrl = 'https://' . self::DOMAIN . '/gallery/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $aMatches = [];
        $sReg = '~"items":\[([^\]]+)\]~um';
        if (!preg_match($sReg, (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $aJson = \GuzzleHttp\json_decode('[' . $aMatches[1] . ']', true);
        $index = 0;
        foreach ($aJson as $aItem) {
            $sFilename = urldecode($aItem['url']);
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
