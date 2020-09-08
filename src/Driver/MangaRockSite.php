<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class MangaRockSite extends DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangarock.site';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Domain to download from
     * @return string
     */
    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var Traversable $oChapters
         * @var AbstractNode $oChapter
         * @var AbstractNode $oImg
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('.chapter-list a');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $oChapter) {
            $oResponse = $this->getClient()->get($oChapter->getAttribute('href'));
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

    protected function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
