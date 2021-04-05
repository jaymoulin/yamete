<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class HMangaSearcherCom extends DriverAbstract
{
    private const DOMAIN = 'hmangasearcher.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(www\.)?(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/c/(?<album>[^/?]+)/?~',
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
        $iPage = 1;
        $index = 1;
        $iChapter = 1;
        $aReturn = [];
        do {
            $bHasNext = false;
            $oRes = $this->getClient()
                ->request(
                    'GET',
                    'http://www.' . self::DOMAIN . '/' .
                    implode('/', ['c', $this->aMatches['album'], $iChapter, $iPage])
                );
            $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody());
            $oNextPage = $oParser->find('ul.pagination .next')[0];
            $oNextChapter = $oParser->find('div.mgch a')[2];
            $oImg = $oParser->find('div.row img.center-block')[0];
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
            if (!str_contains($oNextPage->getAttribute('class'), 'disabled')) {
                $bHasNext = true;
                $iPage++;
            }
            if (!$bHasNext && !str_contains($oNextChapter->getAttribute('class'), 'disabled')) {
                $bHasNext = true;
                $iPage = 1;
                $iChapter++;
            }
        } while ($bHasNext);
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
