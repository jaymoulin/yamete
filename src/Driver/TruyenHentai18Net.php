<?php

namespace Yamete\Driver;

class TruyenHentai18Net extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'truyenhentai18.net';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>.+)\.html$~',
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
         * @var \PHPHtmlParser\Dom\AbstractNode $oLink
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.list-chapter a') as $oLink) {
            $oRes = $this->getClient()->request('GET', $oLink->getAttribute('href'));
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#content-fiximg img') as $oImg) {
                $sFilename = $oImg->getAttribute('src');
                if (strpos($sFilename, '.gif') !== false) {
                    continue;
                }
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
