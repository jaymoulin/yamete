<?php

namespace Yamete\Driver;

use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Client;

class LectorTmoCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'lectortmo.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.']) . '/library/([^/]+)/([0-9]+)/(?<album>[^./]+)~',
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
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $oLink
         * @var \PHPHtmlParser\Dom\AbstractNode $oImage
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string) $oResult->getBody())->find('.formSendChapter');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 1;
        foreach ($aChapters as $oLink) {
            $oResult = $this->getClient()->request('GET', $oLink->getAttribute('action'));
            $sBody = (string)$oResult->getBody();
            $oPages = $this->getDomParser()->load($sBody)->find('#viewer-pages-select option');
            $aMatches = [];
            preg_match('~copyToClipboard\(\'([^\']+)\'~', $sBody, $aMatches);
            $sUrl = $aMatches[1];
            foreach ($oPages as $oPage) {
                $oResult = $this->getClient()->request('GET', $sUrl . '/' . $oPage->getAttribute('value'));
                $oImage = $this->getDomParser()->load((string)$oResult->getBody())->find('#main-container img')[0];
                $sFilename = $oImage->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
