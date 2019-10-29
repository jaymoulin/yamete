<?php

namespace Yamete\Driver;

class LolHentaiNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'lolhentai.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/index\?/collections/view/(?<album>[^?/]+)~',
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
         * @var \PHPHtmlParser\Dom\AbstractNode[] $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $oHref
         */
        $sUrl = 'https://www.' . self::DOMAIN . "/index?/collections/view/{$this->aMatches['album']}";
        $oRes = $this->getClient()->request('GET', "$sUrl&start=0");
        $oContent = $this->getDomParser()->load((string)$oRes->getBody());
        $aReturn = [];
        $index = 0;
        $oChapters = $oContent->find('.pagination ul li a');
        $iMaxPage = 0;
        foreach ($oChapters as $oLink) {
            $iMaxPage = $iMaxPage >= (int)$oLink->text ? $iMaxPage : (int)$oLink->text;
        }
        for ($iPage = 1; $iPage <= $iMaxPage; $iPage++) {
            $oRes = $this->getClient()->request('GET', $sUrl . '&start=' . (($iPage - 1) * 50));
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.gthumb a') as $oHref) {
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
