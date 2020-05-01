<?php

namespace Yamete\Driver;

use PHPHtmlParser\Dom\AbstractNode;


class HentaiNexusCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentainexus.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/view/(?<album>[0-9]+)$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var AbstractNode $oImg
         * @var AbstractNode[] $oPages
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $oPages = $this->getDomParser()->load((string)$oResult->getBody())->find('.columns a');
        $index = 0;
        $aReturn = [];
        foreach ($oPages as $oPage) {
            $sUrl = 'https://' . self::DOMAIN . $oPage->getAttribute('href');
            if (!preg_match('~/read/([0-9]+)/([0-9]+)~', $sUrl)) {
                continue;
            }
            $oResult = $this->getClient()->request('GET', $sUrl);
            $oImg = $this->getDomParser()->load((string)$oResult->getBody())->find('#currImage')[0];
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }
}
