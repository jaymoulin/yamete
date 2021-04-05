<?php

namespace Yamete\Driver;

use Generator;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Traversable;
use Yamete\DriverAbstract;

class HeavenToonCom extends DriverAbstract
{
    private const DOMAIN = 'heaventoon.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://ww[0-9]\.(' . strtr($this->getDomain(), ['.' => '\.']) . ')/(?<album>[^/]+)~',
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
     * @return array
     * @throws GuzzleException
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function getDownloadables(): array
    {
        /**
         * @var Traversable $oChapters
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $index = 0;
        $aReturn = [];
        foreach ($this->getChaptersFromSource((string)$oRes->getBody()) as $sChapterUrl) {
            $oRes = $this->getClient()->request('GET', $sChapterUrl);
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.chapter-content-inner img') as $oImg) {
                $sFilename = trim($oImg->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    /**
     * Generator to yield chapter url for a given source code
     * @param string $sBody
     * @return Generator
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function getChaptersFromSource(string $sBody): Generator
    {
        $sRegExp = '~<h2 class="chap"><a href="(?<chapter>[^"]+)">~';
        $aMatches = [];
        if (preg_match_all($sRegExp, $sBody, $aMatches)) {
            $aChapters = $aMatches['chapter'];
            krsort($aChapters);
            foreach ($aChapters as $sChapterUrl) {
                yield $sChapterUrl;
            }
        } else {
            $oChapters = $this->getDomParser()->loadStr($sBody)->find('.container option');
            /** @var Traversable $oChapters */
            $aChapters = iterator_to_array($oChapters);
            foreach ($aChapters as $oChapter) {
                yield $oChapter->getAttribute('value');
            }
        }
    }

    protected function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
