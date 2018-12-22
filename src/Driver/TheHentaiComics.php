<?php

namespace Yamete\Driver;

class TheHentaiComics extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'thehentaicomics.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)/~',
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
        $aReturn = [];
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.size-full') as $oImg) {
            /* @var \PHPHtmlParser\Dom\AbstractNode $oImg */
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 3, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
