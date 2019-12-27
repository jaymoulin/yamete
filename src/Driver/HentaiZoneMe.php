<?php

namespace Yamete\Driver;

class HentaiZoneMe extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaizone.me';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)~',
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
        /**
         * @var \Traversable $oChapters
         * @var \PHPHtmlParser\Dom\AbstractNode $oChapter
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $sUrl = 'https://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $oChapters = $this->getDomParser()->load((string)$oRes->getBody())->find('.fg_thumbnail a');
        $index = 0;
        $aReturn = [];
        foreach ($oChapters as $oImg) {
            $sFilename = $oImg->getAttribute('href');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
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
