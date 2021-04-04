<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class LolHentaiNet extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'lolhentai.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/index\?/collections/view/(?<album>[^?/]+)~',
            $this->sUrl,
            $this->aMatches
        ) or (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/index\?/category/(?<album>[^?/]+)~',
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
         * @var AbstractNode[] $oChapters
         * @var AbstractNode $oHref
         */
        $sUrl = $this->sUrl;
        $oRes = $this->getClient()->request('GET', "$sUrl&start=0");
        $oContent = $this->getDomParser()->loadStr((string)$oRes->getBody());
        $aReturn = [];
        $index = 0;
        $oChapters = $oContent->find('.pagination li a');
        $iMaxPage = 1;
        foreach ($oChapters as $oLink) {
            $iMaxPage = $iMaxPage >= (int)$oLink->text ? $iMaxPage : (int)$oLink->text;
        }
        for ($iPage = 1; $iPage <= $iMaxPage; $iPage++) {
            $oRes = $this->getClient()->request('GET', $sUrl . '&start=' . (($iPage - 1) * 50));
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.gthumb a') as $oHref) {
                $sFilename = 'https://www.' . self::DOMAIN . '/' . $oHref->getAttribute('data-src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
