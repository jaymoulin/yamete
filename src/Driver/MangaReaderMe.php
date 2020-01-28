<?php

namespace Yamete\Driver;

use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Client;

class MangaReaderMe extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangareader.me';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>.+)~',
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode[] $oPages
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->load((string)$oResult->getBody())->find('.chapters-wrapper a.chap');
        $aChapters = iterator_to_array($oChapters);
        krsort($aChapters);
        $aReturn = [];
        $index = 0;
        foreach ($aChapters as $oLink) {
            $oResult = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            $sRegExp = '~<p id=arraydata style=display:none>([^<]+)</p>~';
            $aMatches = [];
            if (!preg_match($sRegExp, (string)$oResult->getBody(), $aMatches)) {
                continue;
            }
            foreach (explode(',', $aMatches[1]) as $sFilename) {
                $sFilename = 'https:' . $sFilename;
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename(substr($sFilename, 0, strpos($sFilename, '?')));
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
