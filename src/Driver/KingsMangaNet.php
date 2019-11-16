<?php

namespace Yamete\Driver;

class KingsMangaNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'kingsmanga.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)/~',
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
        $sUrl = 'https://www.' . self::DOMAIN . '/manga/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('table.table tr a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sUrl = $oLink->getAttribute('href');
            if (!$sUrl) {
                continue;
            }
            $oRes = $this->getClient()->request('GET', $sUrl);
            $aChap = [];
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.post-content img') as $oImg) {
                $sFilename = $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aChap[$sBasename] = $sFilename;
            }
            $aChap = array_reverse($aChap);
            $aReturn = array_merge($aReturn, $aChap);
        }
        return array_reverse($aReturn);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
