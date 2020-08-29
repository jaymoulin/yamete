<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class AsmHentai extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'asmhentai.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/g/(?<album>[^/?]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()
            ->request('GET', 'https://' . self::DOMAIN . '/gallery/' . $this->aMatches['album'] . '/1/');
        $aReturn = [];
        $aMatches = [];
        if (!preg_match('~Page 1 of ([0-9]+)~', (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $iNbPages = (int)$aMatches[1];
        for ($iPage = 1; $iPage <= $iNbPages; $iPage++) {
            $sLink = 'https://' . self::DOMAIN . '/gallery/' . $this->aMatches['album'] . '/' . $iPage . '/';
            $oRes = $this->getClient()->request('GET', $sLink);
            $aMatches = [];
            if (!preg_match_all('~src="([^"]+)"~', (string)$oRes->getBody(), $aMatches)) {
                return [];
            }
            foreach ($aMatches[1] as $sImg) {
                $sFilename = 'https:' . $sImg;
                if (strpos($sFilename, 'images.') === false) {
                    continue;
                }
                $aReturn[$this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename)] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
