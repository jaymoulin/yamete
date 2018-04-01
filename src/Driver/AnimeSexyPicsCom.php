<?php

namespace Yamete\Driver;

class AnimeSexyPicsCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'animesexypics.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/gallery/(?<album>[^/]+)/index\.html$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables()
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $sBody = str_replace(
            '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1;charset=windows-1252" />',
            '',
            (string)$oRes->getBody()
        );
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load($sBody)->find('.player a') as $oLink) {
            /**
             * @var \DOMElement $oLink
             * @var \DOMElement $oImg
             */
            $sUrl = 'http://' . $this->getDomain() . $oLink->getAttribute('href');
            $oRes = $this->getClient()->request('GET', $sUrl);
            $sBody = str_replace(
                '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1;charset=windows-1252" />',
                '',
                (string)$oRes->getBody()
            );
            $oImg = $this->getDomParser()->load($sBody)->find('center img')[0];
            $sFilename = $oImg->getAttribute('src');
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
