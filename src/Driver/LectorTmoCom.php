<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class LectorTmoCom extends DriverAbstract
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
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var Traversable $oChapters
         * @var AbstractNode $oLink
         * @var AbstractNode $oImage
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string)$oResult->getBody())->find('.list-group-item a.btn-default');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 1;
        foreach ($aChapters as $oLink) {
            $oResult = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $sBody = (string)$oResult->getBody();
            $oPages = $this->getDomParser()->load($sBody)->find('#viewer-pages-select option');
            $aMatches = [];
            preg_match('~copyToClipboard\(\'([^\']+)\'~', $sBody, $aMatches);
            $sUrl = $aMatches[1];
            foreach ($oPages as $oPage) {
                usleep(20);
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
