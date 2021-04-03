<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

class HentaiFantasy extends DriverAbstract
{
    private $aMatches = [];
    private const DOMAIN = 'hentaifantasy.it';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.']) . '/series/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws GuzzleException
     */
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.group .element .title a') as $oLink) {
            /**
             * @var AbstractNode $oLink
             */
            $sLink = $oLink->getAttribute('href');
            $aMatches = [];
            $bFound = preg_match(
                '~https?://www\.' . strtr(self::DOMAIN, ['.' => '\.'])
                . '/read/(?<album>[^/]+)/(?<lang>[^/]{2})/(?<chapters>.+)/~',
                $sLink,
                $aMatches
            );
            if (!$bFound) {
                continue;
            }
            $oRes = $this->getClient()->request('GET', $sLink);
            $sSelector = '.topbar_right .dropdown li a';
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find($sSelector) as $oPage) {
                /**
                 * @var AbstractNode $oPage
                 * @var AbstractNode $oImg
                 */
                $oRes = $this->getClient()->request('GET', $oPage->getAttribute('href'));
                $oImg = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('img.open')[0];
                $sFilename = $oImg->getAttribute('src');
                $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                    . $aMatches['chapters'] . DIRECTORY_SEPARATOR
                    . str_pad(++$index, 5, '0', STR_PAD_LEFT) . '-'
                    . basename($sFilename);
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
