<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Traversable;
use Yamete\DriverAbstract;

class EggPornComicsCom extends DriverAbstract
{
    private const DOMAIN = 'eggporncomics.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/comics/(?<id>[^/]+)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function getDownloadables(): array
    {
        /**
         * @var Traversable $oPages
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
            if (!str_contains($sLink, '?page=')) {
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
