<?php

namespace Yamete\Driver;

class ReadhentAi extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    private $i = 0;
    const DOMAIN = 'readhent.ai';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^?]+)~',
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
         * @var \PHPHtmlParser\Dom\AbstractNode[] $oPages
         */
        $this->sUrl = "https://{$this->getDomain()}/{$this->aMatches['category']}/{$this->aMatches['album']}";
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $oContent = $this->getDomParser()->load((string)$oRes->getBody());
        $this->aReturn = [];
        $this->i = 0;
        $oPages = $oContent->find('.preview_thumb a img');
        foreach ($oPages as $oLink) {
            $sFilename = str_replace('thumb_', '', $oLink->getAttribute('href'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($this->i++, 5, '0', STR_PAD_LEFT)
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
