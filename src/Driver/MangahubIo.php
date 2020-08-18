<?php


namespace Yamete\Driver;

use iterator;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class MangahubIo extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangahub.io';

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
        $oChapters = $this->getDomParser()->load((string)$oResponse->getBody())->find('.tab-content li a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $oLink) {
            $sUrl = $oLink->getAttribute('href');
            $aMatches = [];
            if (!preg_match('~/chapter/(?<name>[^/]+)/chapter-(?<chapter>[0-9]+)~', $sUrl, $aMatches)) {
                continue;
            }
            $oResponse = $this->getClient()->request('POST', "https://api.mghubcdn.com/graphql", [
                'json' => [
                    'query' => "{chapter(x:m01,slug:\"${aMatches['name']}\",number:${aMatches['chapter']}){id,title,mangaID,number,slug,date,pages,noAd,manga{id,title,slug,mainSlug,author,isWebtoon,isYaoi,isPorn,isSoftPorn,unauthFile,isLicensed}}}",
                ],
            ]);
            $aChapter = \GuzzleHttp\json_decode((string)$oResponse->getBody(), true);
            $aPages = \GuzzleHttp\json_decode($aChapter['data']['chapter']['pages'], true);
            foreach ($aPages as $sFile) {
                $sFilename = "https://img.mghubcdn.com/file/imghub/" . $sFile;
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
