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

class DoujinReader extends DriverAbstract
{
    private const DOMAIN = 'doujinreader.com';
    private array $aMatches = [];

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/doujin/hentai/manga/comic/(?<album>[^/]+)~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        $oLink = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('.wallpaper_item a')[0];
        $sLink = $oLink->getAttribute('href');
        do {
            $oRes = $this->getClient()->request('GET', $sLink);
            $oParser = $this->getDomParser()->loadStr((string)$oRes->getBody());
            $oNext = $oParser->find('.next_wallpaper')[0];
            $bHasNext = (bool)$oNext;
            $oImg = $oParser->find('img')[0];
            if (!$oImg) {
                continue;
            }
            if ($bHasNext) {
                $sLink = trim(str_replace('window.location = ', '', $oNext->getAttribute('onclick')), '\';');
            }
            $sFilename = html_entity_decode($oImg->getAttribute('src'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . $index . '.jpg';
            $aReturn[$sBasename] = $sFilename;
        } while ($bHasNext);
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
