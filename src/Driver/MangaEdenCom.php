<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Utils;
use iterator;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class MangaEdenCom extends DriverAbstract
{
    private const DOMAIN = 'mangaeden.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/[a-z]{2}/[^/]+/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
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
         * @var iterator $oChapters
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oChapters = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('a.chapterLink');
        $aChapters = iterator_to_array($oChapters);
        $aReturn = [];
        krsort($aChapters);
        $index = 0;
        foreach ($aChapters as $oChapter) {
            $sLink = 'https://' . self::DOMAIN . $oChapter->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            $aMatches = [];
            if (!preg_match('~var pages = (\[[^]]+]);~', (string)$oRes->getBody(), $aMatches)) {
                continue;
            }
            foreach (Utils::jsonDecode($aMatches[1], true) as $aInfo) {
                $sFilename = 'http:' . $aInfo['fs'];
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
