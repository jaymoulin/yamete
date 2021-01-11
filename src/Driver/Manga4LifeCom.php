<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use iterator;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class Manga4LifeCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'manga4life.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
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
         * @var iterator $oChapters
         * @var AbstractNode[] $aChapters
         * @var AbstractNode $oPage
         * @var AbstractNode $oImg
         */
        $sAlbumName = $this->aMatches['album'];
        $sLink = 'https://' . self::DOMAIN . "/read-online/${sAlbumName}-chapter-1-page-1.html";
        $oRes = $this->getClient()->request('GET', $sLink);
        $aMatches = [];
        if (!preg_match('~vm\.CHAPTERS = (\[[^\]]+\]);~', (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $index = 0;
        $aReturn = [];
        $aData = \GuzzleHttp\json_decode($aMatches[1], true);
        $iChapter = 1;
        foreach ($aData as $aChapter) {
            $iNbPage = (int)$aChapter['Page'];
            for ($iPage = 1; $iPage <= $iNbPage; $iPage++) {
                $sFilename = 'https://official-ongoing.ivalice.us/manga/'
                    . $sAlbumName . '/' . str_pad($iChapter, 4, '0', STR_PAD_LEFT) . '-'
                    . str_pad($iPage, 3, '0', STR_PAD_LEFT) . '.png';
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            $iChapter++;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
