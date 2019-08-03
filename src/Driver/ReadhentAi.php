<?php

namespace Yamete\Driver;

class ReadhentAi extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $index = 0;
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
        $aReturn = [];
        $this->index = 0;
        $oPages = $oContent->find('.preview_thumb a img');
        foreach ($oPages as $oImg) {
            $sFilename = str_replace('thumb_', '', $oImg->getAttribute('src'));
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($this->index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        var_dump($aReturn);
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
