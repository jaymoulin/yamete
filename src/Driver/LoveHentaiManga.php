<?php

namespace Yamete\Driver;

class LoveHentaiManga extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'lovehentaimanga.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/hentai_manga/index\.php/(?<album>[^/]+)/[^/?]+$~',
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
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        $sPageSelector = '.weatimages_pages_navigator a';
        $sSelector = 'img.weatimages_thumb_border';
        $iNbPages = count($this->getDomParser()->load((string)$oRes->getBody())->find($sPageSelector)) / 2;
        for ($iPage = 1; $iPage <= ($iNbPages ?: 1); $iPage++) {
            $oRes = $this->getClient()->request('GET', $this->sUrl . '?page=' . $iPage);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($sSelector) as $oImg) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sFilename = 'https://' . self::DOMAIN . str_replace('thumb', 'resize', $oImg->getAttribute('src'));
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
