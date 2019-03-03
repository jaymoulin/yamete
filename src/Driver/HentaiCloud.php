<?php

namespace Yamete\Driver;

class HentaiCloud extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaicloud.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<category>[^/]+)/(?<subCategory>[^/]+)/' .
                '(?<albumId>[^/]+)/(?<album>[^/?]+)/?~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $iIndex = 0;
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('div.thumb-overlay a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $sLink = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.photo-holder img') as $oImg) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                 */
                $sFilename = 'https://www.' . self::DOMAIN . $oImg->getAttribute('src');
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($iIndex++, 5, '0', STR_PAD_LEFT) .
                    basename($sFilename);
                $aReturn[$sPath] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
