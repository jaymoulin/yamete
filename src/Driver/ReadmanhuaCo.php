<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

class ReadmanhuaCo extends DriverAbstract
{
    private const DOMAIN = 'readmanhua.co';
    protected $aMatches = [];

    public function canHandle(): bool
    {
        $sReg = '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^/]+)~';
        return (bool)preg_match($sReg, $this->sUrl, $this->aMatches);
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
        $sUrl = 'https://' . self::DOMAIN . '/' . $this->aMatches['category'] . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aMatches = [];
        $sRegExp = '~<li class="wp-manga-chapter[^"]+">[^<]+<a href="([^"]+)~us';
        if (!preg_match_all($sRegExp, (string)$oRes->getBody(), $aMatches)) {
            return [];
        }
        $aChapters = $aMatches[1];
        krsort($aChapters);
        $index = 0;
        $aReturn = [];
        foreach ($aChapters as $sChapter) {
            $oRes = $this->getClient()->request('GET', $sChapter);
            $aMatches = [];
            $sRegExp = '~<img src="([^"]+)~';
            if (!preg_match_all($sRegExp, (string)$oRes->getBody(), $aMatches)) {
                return [];
            }
            foreach ($aMatches[1] as $sFilename) {
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
