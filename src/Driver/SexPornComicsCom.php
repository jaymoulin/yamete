<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class SexPornComicsCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'sexporncomics.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<id>[^/]+)/(?<album>[^/]+)~',
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
                $this->aMatches['id'],
                $this->aMatches['album'],
            ]
        );
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->load((string)$oRes->getBody())->find('.king-q-view-content img');
        $index = 0;
        $aReturn = [];
        foreach ($oPages as $oLink) {
            $sFilename = $oLink->getAttribute('src');
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
