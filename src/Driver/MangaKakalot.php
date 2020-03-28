<?php

namespace Yamete\Driver;

class MangaKakalot extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    const DOMAIN = 'mangakakalot.com';

    public function canHandle(): bool
    {
        $bRead = (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/read-(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
        return $bRead || (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/chapter/(?<album>[^/]+)~',
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
        $this->aReturn = [];
        $this->getLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getLinks(string $sUrl): void
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $bFound = false;
        $aChapters = iterator_to_array($this->getDomParser()->load((string)$oRes->getBody())->find('.chapter-list a'));
        krsort($aChapters);
        foreach ($aChapters as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $this->getLinks($oLink->getAttribute('href'));
            $bFound = true;
        }
        if ($bFound) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#vungdoc img') as $oImg) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR
                . str_pad(count($this->aReturn) + 1, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $this->aReturn[$sBasename] = $sFilename;
        }
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
