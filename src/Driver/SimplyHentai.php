<?php

namespace Yamete\Driver;

class SimplyHentai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'simply-hentai.com';

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://(?<domain>[^.]+\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) . '/(?<album>[^?]+))~',
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
        $sUrl = 'https://' . $this->aMatches['domain'] . '/' . $this->aMatches['album'] . '/all-pages';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a.image-preview') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sContent = (string)$this->getClient()->request('GET', $oLink->getAttribute('href'))->getBody();
            $sRegExp = '~<div data-react-class="album/Read" data-react-props="([^"]+)">~';
            if (!preg_match($sRegExp, $sContent, $aMatches)) {
                continue;
            }
            $sProps = html_entity_decode($aMatches[1]);
            $aJson = \GuzzleHttp\json_decode($sProps, true);
            $sFilename = $aJson['image']['sizes']['full'];
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR .
                str_pad($i++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
