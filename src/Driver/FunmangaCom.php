<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use iterator;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;


class FunmangaCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'funmanga.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
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
         * @var AbstractNode[] $oPages
         * @var AbstractNode $oImg
         */
        $sUrl = 'https://www.' . self::DOMAIN . '/' . $this->aMatches['album'] . '/';
        $oResult = $this->getClient()->request('GET', $sUrl);
        $oChapters = $this->getDomParser()->loadStr((string)$oResult->getBody())->find('.chapter-list a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters as $oChapter) {
            $oResult = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
            $sRegExp = '~var images = ([^;]+);~';
            $aMatches = [];
            if (!preg_match($sRegExp, (string)$oResult->getBody(), $aMatches)) {
                return [];
            }
            $aObjects = \GuzzleHttp\json_decode($aMatches[1], true);
            foreach ($aObjects as $aPage) {
                $sFilename = trim($aPage['url']);
                $sFilename = substr($sFilename, 0, strpos($sFilename, '?'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
