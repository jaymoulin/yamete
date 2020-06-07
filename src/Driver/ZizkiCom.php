<?php

namespace Yamete\Driver;

class ZizkiCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'zizki.com';

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \PHPHtmlParser\Dom\AbstractNode $oLink
         * @var \PHPHtmlParser\Dom\AbstractNode[] $oPages
         */
        $sUrl = 'https://' . self::DOMAIN . '/' . implode('/', [$this->aMatches['author'], $this->aMatches['album']]);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oPages = $this->getDomParser()->load((string)$oRes->getBody())->find('.xbox-inner a');
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
