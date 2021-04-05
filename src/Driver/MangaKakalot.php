<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

class MangaKakalot extends DriverAbstract
{
    private const DOMAIN = 'mangakakalot.com';
    private array $aMatches = [];
    private array $aReturn = [];

    public function canHandle(): bool
    {
        $bRead = (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/read-(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
        return $bRead || preg_match(
                '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/chapter/(?<album>[^/]+)~',
                $this->sUrl,
                $this->aMatches
            );
    }

    /**
     * @return array
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws GuzzleException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    public function getDownloadables(): array
    {
        $this->aReturn = [];
        $this->getLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws ChildNotFoundException
     * @throws CircularException
     * @throws ContentLengthException
     * @throws GuzzleException
     * @throws LogicalException
     * @throws NotLoadedException
     * @throws StrictException
     */
    private function getLinks(string $sUrl): void
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $bFound = false;
        $aChapters = iterator_to_array($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.chapter-list a'));
        krsort($aChapters);
        foreach ($aChapters as $oLink) {
            $this->getLinks($oLink->getAttribute('href'));
            $bFound = true;
        }
        if ($bFound) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.container-chapter-reader img') as $oImg) {
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR
                . str_pad(count($this->aReturn) + 1, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $this->aReturn[$sBasename] = $sFilename;
        }
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Referer' => $this->sUrl]]);
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
