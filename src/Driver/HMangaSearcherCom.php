<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HMangaSearcherCom extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hmangasearcher.com';

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
     */
    public function getDownloadables(): array
    {
        $iPage = 1;
        $index = 1;
        $iChapter = 1;
        $aReturn = [];
        do {
            $bHasNext = false;
            /**
             * @var AbstractNode $oNextPage
             * @var AbstractNode $oNextChapter
             * @var AbstractNode $oImg
             */
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
            if (strpos($oNextPage->getAttribute('class'), 'disabled') === false) {
                $bHasNext = true;
                $iPage++;
            }
            if (!$bHasNext && strpos($oNextChapter->getAttribute('class'), 'disabled') === false) {
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
