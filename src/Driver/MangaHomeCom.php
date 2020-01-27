<?php


namespace Yamete\Driver;

use Yamete\DriverAbstract;

class MangaHomeCom extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangahome.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables(): array
    {
        /**
         * @var \iterator $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $aChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $oPages
         * @var \PHPHtmlParser\Dom\AbstractNode $oImage
         */
        $sStartUrl = 'https://www.'. self::DOMAIN;
        $sUrl = $sStartUrl . '/manga/' . $this->aMatches['album'];
        $oResponse = $this->getClient()->get($sUrl);
        $oChapters = $this->getDomParser()->load((string)$oResponse->getBody())->find('.detail-chlist a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $oLink) {
            $oResponse = $this->getClient()->get($sStartUrl . $oLink->getAttribute('href'));
            $oPages = $this->getDomParser()->load((string)$oResponse->getBody())->find('.mangaread-pagenav option');
            foreach ($oPages as $oPage) {
                $oResponse = $this->getClient()->get($sStartUrl . $oPage->getAttribute('value'));
                $oImage = $this->getDomParser()->load((string)$oResponse->getBody())->find('#image')[0];
                if (!$oImage) {
                    continue;
                }
                $oImage = $oImage[0];
                $sFilename = 'https:' . $oImage->getAttribute('src');
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
