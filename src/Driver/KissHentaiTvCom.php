<?php

namespace Yamete\Driver;

class KissHentaiTvCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    private $index = 0;
    const DOMAIN = 'kisshentaitv.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDomain(): string
    {
        return self::DOMAIN;
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var \PHPHtmlParser\Dom\AbstractNode $oLink
         */
        $this->sUrl = "http://www.{$this->getDomain()}/{$this->aMatches['album']}/";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oContent = $this->getDomParser()->load((string)$oRes->getBody());
        $this->aReturn = [];
        $this->index = 0;
        $oChapters = $oContent->find('figure.gallery-item a');
        foreach ($oChapters as $oLink) {
            $sFilename = $oLink->getAttribute('href');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($this->index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $this->aReturn[$sBasename] = $sFilename;
        }
        return $this->aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
