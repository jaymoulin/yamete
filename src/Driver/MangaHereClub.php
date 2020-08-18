<?php


namespace Yamete\Driver;

use iterator;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class MangaHereClub extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangahere.club';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(manga|chapter)/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables(): array
    {
        /**
         * @var iterator $oChapters
         * @var AbstractNode[] $aChapters
         * @var AbstractNode[] $oPages
         */
        $sStartUrl = 'https://' . self::DOMAIN;
        $sUrl = $sStartUrl . '/manga/' . $this->aMatches['album'];
        $oResponse = $this->getClient()->get($sUrl);
        $oChapters = $this->getDomParser()->load((string)$oResponse->getBody())->find('.chap a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $oLink) {
            $oResponse = $this->getClient()->get($oLink->getAttribute('href'));
            $sRegExp = '~<p id=arraydata style=display:none>([^<]+)~us';
            $aMatches = [];
            if (!preg_match($sRegExp, (string)$oResponse->getBody(), $aMatches)) {
                return [];
            }
            $aPages = explode(',', $aMatches[1]);
            foreach ($aPages as $sFilename) {
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
