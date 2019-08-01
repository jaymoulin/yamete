<?php

namespace Yamete\Driver;

class AnimeSexyPicsCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'animesexypics.com';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
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
    public function getDownloadables(): array
    {
        $oRes = $this->getClient()->request('GET', $this->sUrl);
        $sBody = str_replace(
            '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1;charset=windows-1252" />',
            '',
            (string)$oRes->getBody()
        );
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load($sBody)->find('.player a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
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
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                . '-' . basename($sFilename);
            $aReturn[$sBasename] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
