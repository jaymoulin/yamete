<?php

namespace Yamete\Driver;

class HDHentaiTv extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hdhentai.tv';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/photos/(?<album>[^/]+)/~',
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
        $sUrl = 'https://' . self::DOMAIN . '/photos/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#lightgallery a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $sFilename = $oLink->getAttribute('href');
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
