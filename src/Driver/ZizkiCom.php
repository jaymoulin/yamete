<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class ZizkiCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'zizki.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<author>[^/]+)/(?<album>[^/]+)~',
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
         * @var AbstractNode[] $oPages
         */
        $sUrl = 'https://' . self::DOMAIN . '/' . implode('/', [$this->aMatches['author'], $this->aMatches['album']]);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('.xbox-inner a');
        $index = 0;
        $aReturn = [];
        foreach ($oPages as $oLink) {
            $sFileToDownload = $oLink->getAttribute('href');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFileToDownload);
            $aReturn[$sBasename] = $sFileToDownload;
        }
        return $aReturn;
    }

    /**
     * Where to download files
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
