<?php

namespace Yamete\Driver;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class MangaKakalot extends DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    const DOMAIN = 'mangakakalot.com';

    public function canHandle(): bool
    {
        $bRead = (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/read-(?<album>[^/]+)~',
            $this->sUrl,
            $this->aMatches
        );
        return $bRead || (bool)preg_match(
                '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/chapter/(?<album>[^/]+)~',
                $this->sUrl,
                $this->aMatches
            );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $this->aReturn = [];
        $this->getLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws GuzzleException
     */
    private function getLinks(string $sUrl): void
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $bFound = false;
        $aChapters = iterator_to_array($this->getDomParser()->load((string)$oRes->getBody())->find('.chapter-list a'));
        krsort($aChapters);
        foreach ($aChapters as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $this->getLinks($oLink->getAttribute('href'));
            $bFound = true;
        }
        if ($bFound) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#vungdoc img') as $oImg) {
            /**
             * @var AbstractNode $oImg
             */
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR
                . str_pad(count($this->aReturn) + 1, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $this->aReturn[$sBasename] = $sFilename;
        }
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function getClient(array $aOptions = []): Client
    {
        return parent::getClient(['headers' => ['Referer' => $this->sUrl]]);
    }
}
