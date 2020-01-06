<?php

namespace Yamete\Driver;

use Yamete\DriverInterface;

class HentaiRead extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    const DOMAIN = 'hentairead.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/hentai/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $this->aReturn = [];
        $this->getLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getLinks(string $sUrl): void
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $bFound = false;
        $index = 0;
        $aMatches = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('li.wp-manga-chapter a') as $oLink) {
            /* @var \PHPHtmlParser\Dom\AbstractNode $oLink */
            $this->getLinks($oLink->getAttribute('href'));
            $bFound = true;
        }
        if ($bFound) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        if (!preg_match('~var chapter_preloaded_images = ([^]]+])~', (string)$oRes->getBody(), $aMatches)) {
            return;
        }
        $aPages = \GuzzleHttp\json_decode($aMatches[1], true);
        foreach ($aPages as $sFilename) {
            $iPos = strpos($sFilename, '?');
            $sFilename = substr($sFilename, 0, $iPos);
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $this->aReturn[$sBasename] = $sFilename;
        }
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    public function clean(): DriverInterface
    {
        $this->aReturn = [];
        return parent::clean();
    }
}
