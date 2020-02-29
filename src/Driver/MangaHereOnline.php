<?php

namespace Yamete\Driver;

class MangaHereOnline extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangahere.online';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/(?<album>[^/]+)$~',
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
         * @var \PHPHtmlParser\Dom\AbstractNode $oLink
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $aOptions = ['cleanupInput' => false];
        $oChapters = $this->getDomParser()
            ->load((string)$oResult->getBody(), $aOptions)
            ->find('.chapter-list a');
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
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
