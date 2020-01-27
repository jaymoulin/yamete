<?php


namespace Yamete\Driver;

use Yamete\DriverAbstract;

class FanFoxNet extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'fanfox.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
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
        $sStartUrl = 'https://'. self::DOMAIN;
        $sUrl = $sStartUrl . '/manga/' . $this->aMatches['album'] . '/';
        $oResponse = $this->getClient()->get($sUrl);
        $oChapters = $this->getDomParser()->load((string)$oResponse->getBody())->find('.detail-main-list li > a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $oLink) {
            $sCurrentUrl = $sStartUrl . $oLink->getAttribute('href');
            if (false === strpos($sCurrentUrl, '/1.html')) {
                continue;
            }
            $oResponse = $this->getClient()->get($sCurrentUrl);
            $iPageCount = 0;
            $oPages = $this->getDomParser()->load((string)$oResponse->getBody())->find('.pager-list-left a');
            foreach ($oPages as $oPage) {
                $iCurrentPage = $oPage->getAttribute('data-page');
                $iPageCount = $iCurrentPage >= $iPageCount ? $iCurrentPage : $iPageCount;
            }
            for ($iCurrentPage = 1; $iCurrentPage <= $iPageCount; $iCurrentPage++) {
                $oResponse = $this->getClient()->get(str_replace('/1.html', "/$iCurrentPage.html", $sCurrentUrl));
                $oImage = $this->getDomParser()->load((string)$oResponse->getBody())->find('.reader-main-img')[0];
                if (!$oImage) {
                    continue;
                }
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

    public function getClient(array $aOptions = []): \GuzzleHttp\Client
    {
        return parent::getClient(
            [
                'headers' => ['Cookie' => 'isAdult=1'],
            ]
        );
    }
}
