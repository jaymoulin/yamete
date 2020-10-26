<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HentaiCloud extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hentaicloud.com';

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
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $iIndex = 0;
        $aReturn = [];
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('div.thumbnail a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sLink = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sLink);
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.thumbnail img') as $oImg) {
                /**
                 * @var AbstractNode $oImg
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
