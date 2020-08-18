<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class EggPornComicsCom extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'eggporncomics.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/comics/(?<id>[^/]+)/(?<album>[^/]+)~',
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
        /**
         * @var Traversable $oPages
         * @var AbstractNode $oLink
         * @var AbstractNode $oImg
         */
        $sUrl = implode(
            '/',
            [
                'https:/',
                self::DOMAIN,
                'comics',
                $this->aMatches['id'],
                $this->aMatches['album'],
            ]
        );
        $aMatches = [];
        $oRes = $this->getClient()->request('GET', $sUrl);
        if (!preg_match_all('~<a href="([^"]+)"~', (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $index = 0;
        $aReturn = [];
        foreach ($aMatches[1] as $sLink) {
            if (strpos($sLink, '?page=') === false) {
                continue;
            }
            $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $sLink);
            if (!preg_match('~&per-page=1"><img src="([^"]+)"~', (string)$oRes->getBody(), $aMatches)) {
                continue;
            }
            $sFilename = 'https://' . self::DOMAIN . $aMatches[1];
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return array_merge(array_slice($aReturn, -1, 1), array_slice($aReturn, 0, $index - 1));
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
