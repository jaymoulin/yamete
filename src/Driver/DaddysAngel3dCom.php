<?php

namespace Yamete\Driver;


class DaddysAngel3dCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'daddysangel3d.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    public function canHandle()
    {
        return (bool)preg_match(
                '~^https?://galleries\.(' . strtr($this->getDomain(), ['.' => '\.']) .
                ')/(?<album>[^/]+)/index\.html$~',
                $this->sUrl,
                $this->aMatches
            ) ||
            (bool)preg_match(
                '~^https?://galleries\.(' . strtr($this->getDomain(), ['.' => '\.']) .
                ')/pics/(?<album>[0-9]{3})/(?<pic>[0-9]{3}\.jpg)$~',
                $this->sUrl,
                $this->aMatches
            );
    }

    public function getDownloadables()
    {
        if (!isset($this->aMatches['pic'])) {
            /* @var \DOMElement $oLink */
            $oLink = $this->getDomParser()->loadFromUrl($this->sUrl)->find('.thumbs_block .thumbs a')[0];
            $sSrc = $oLink->getAttribute('href');
            if (!preg_match('~/pics/(?<albumId>[0-9]{3})/(?<pic>[0-9]{3})\.jpg~', $sSrc, $aMatches)) {
                throw new \Exception('Unable to determine album from url');
            }
            $this->aMatches['album'] = $aMatches['albumId'];
        }
        $sDomain = self::DOMAIN;
        $sAlbumId = $this->aMatches['album'];
        $sUrl = "http://galleries.$sDomain/pics/$sAlbumId/";
        $aReturn = [];
        for ($i = 1; $i <= 999; $i++) {
            $sFilename = $sUrl . str_pad($i, 3, '0', STR_PAD_LEFT) . '.jpg';
            try {
                $this->getClient()->request('GET', $sFilename);
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            } catch (\Exception $e) {
                return $aReturn;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}

