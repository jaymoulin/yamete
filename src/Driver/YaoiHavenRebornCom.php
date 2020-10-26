<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class YaoiHavenRebornCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'yaoihavenreborn.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/doujinshi/(?<album>[^/]+)~',
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
                'www.' . self::DOMAIN,
                'doujinshi',
                $this->aMatches['album'],
            ]
        );
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->load((string)$oRes->getBody())->find('img.img-fluid');
        $index = 0;
        $aReturn = [];
        foreach ($oPages as $oLink) {
            $sFilename = 'https://' . self::DOMAIN . $oLink->getAttribute('data-src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
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
