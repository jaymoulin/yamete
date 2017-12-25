<?php

namespace Yamete\Driver;

class HentaiFantasy extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentaifantasy.it';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.' . strtr(self::DOMAIN, ['.' => '\.']) . '/series/(?<album>[^/]+)/$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.group .element .title a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             */
            $sLink = $oLink->getAttribute('href');
            $bFound = preg_match(
                '~http://www\.' . strtr(self::DOMAIN, ['.' => '\.'])
                    . '/read/(?<album>[^/]+)/(?<lang>[^/]{2})/(?<chapters>.+)/$~',
                $sLink,
                $aMatches
            );
            if ($bFound) {
                $oRes = $this->getClient()->request('GET', $sLink);
                $sSelector = '.topbar_right .dropdown li a';
                foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($sSelector) as $oPage) {
                    /**
                     * @var \DOMElement $oPage
                     * @var \DOMElement $oImg
                     */
                    $oRes = $this->getClient()->request('GET', $oPage->getAttribute('href'));
                    $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('img.open')[0];
                    $sFilename = $oImg->getAttribute('src');
                    $sPath = $this->getFolder() . DIRECTORY_SEPARATOR
                        . $aMatches['chapters'] . DIRECTORY_SEPARATOR
                        . str_pad(++$i, 5, '0', STR_PAD_LEFT) . '-'
                        . basename($sFilename);
                    $aReturn[$sPath] = $sFilename;
                }
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
