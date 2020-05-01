<?php

namespace Yamete\Driver;

use PHPHtmlParser\Dom\AbstractNode;


class ToomicsCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'toomics.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.'])
            . ')/(?<locale>[a-z]{2})/webtoon/episode/toon/(?<album>[0-9]+)$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * Where to download
     * @return string
     */
    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        /**
         * @var AbstractNode $oImg
         * @var AbstractNode[] $oPages
         */
        $oResult = $this->getClient()->request('GET', $this->sUrl);
        $sRegExp = '~Webtoon\.chkec\(this\);location\.href=\'([^\']+)\'~';
        $aUrls = [];
        if (!preg_match_all($sRegExp, (string)$oResult->getBody(), $aUrls)) {
            return [];
        }
        $index = 0;
        $aReturn = [];
        foreach ($aUrls[1] as $sUrl) {
            if (strpos($sUrl, '/ep/') === false) {
                continue;
            }
            $sUrl = 'https://' . self::DOMAIN . $sUrl;
            $oResult = $this->getClient()->request('GET', $sUrl);
            foreach ($this->getDomParser()->load((string)$oResult->getBody())->find('#viewer-img img') as $oImg) {
                $sFilename = $oImg->getAttribute('data-original');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }
}
