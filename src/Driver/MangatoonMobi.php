<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class MangatoonMobi extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangatoon.mobi';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/[^/]+/(detail|watch)/(?<album>[^/]+)~',
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
         * @var AbstractNode $oLink
         * @var AbstractNode $oImg
         */
        $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . "/en/detail/{$this->aMatches['album']}");
        $aReturn = [];
        $oLink = $this->getDomParser()->load((string)$oRes->getBody())->find('.top-button-wrap > a')[0];
        $oRes = $this->getClient()->request('GET', 'https://' . self::DOMAIN . $oLink->getAttribute('href'));
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.pictures img') as $oImg) {
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename(preg_replace('~\?(.*)$~', '', $sFilename));
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
