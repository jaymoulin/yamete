<?php

namespace Yamete\Driver;

class MangaEdenCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangaeden.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/[a-z]{2}/[^/]+/(?<album>[^/]+)/$~',
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
         * @var \iterator $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $aChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $oPage
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('a.chapterLink');
        $aChapters = iterator_to_array($oChapters);
        $aReturn = [];
        krsort($aChapters);
        $index = 0;
        foreach ($aChapters as $oChapter) {
            $sLink = 'https://' . self::DOMAIN . $oChapter->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            if (!preg_match('~var pages = (\[[^\]]+\]);~', (string)$oRes->getBody(), $aMatches)) {
                continue;
            }
            foreach (\GuzzleHttp\json_decode($aMatches[1], true) as $aInfo) {
                $sFilename = 'http:' . $aInfo['fs'];
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
