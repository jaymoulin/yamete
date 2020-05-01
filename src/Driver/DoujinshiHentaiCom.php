<?php

namespace Yamete\Driver;

class DoujinshiHentaiCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'doujinshihentai.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/manga/index\.php/(?<serie>[^/]+)/(?<album>[^/]+)~',
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
        $sUrl = 'https://' . self::DOMAIN . '/'
            . implode('/', ['manga', 'index.php', $this->aMatches['serie'], $this->aMatches['album']]);
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        $iNbPage = count($this->getDomParser()->load((string)$oRes->getBody())->find('.weatimages_pages_navigator a'));
        for ($iPage = 1; $iPage <= $iNbPage; $iPage++) {
            $oRes = $this->getClient()->request('GET', $sUrl . '/?page=' . $iPage);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('table a img') as $oImg) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sFilename = 'http://' . self::DOMAIN
                    . str_replace('action=thumb', 'action=resize', $oImg->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename(preg_replace('~\?(.*)$~', '', $sFilename));
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
