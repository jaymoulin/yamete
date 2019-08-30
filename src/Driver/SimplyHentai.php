<?php

namespace Yamete\Driver;

class SimplyHentai extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'simply-hentai.com';

    public function canHandle(): bool
    {
        $sMatch = '~^https?://(?<domain>[^.]+\.' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-'])
            . ')/(?<album>[^?]+)(?!all\-pages)~';
        return (bool)preg_match($sMatch, $this->sUrl, $this->aMatches);
    }

    /**
     * @return array|string[]
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadables(): array
    {
        $sUrl = 'https://' . $this->aMatches['domain'] . '/' . $this->aMatches['album'] .
            ($this->aMatches['album']{strlen($this->aMatches['album']) - 1} == '/' ? '' : '/') . 'all-pages';
        $oRes = $this->getClient()->request('GET', $sUrl);
        $aReturn = [];
        $index = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('a.preview') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $sUrl = 'https://www.' . self::DOMAIN . $oLink->getAttribute('href');
            $sContent = (string)$this->getClient()->request('GET', $sUrl)->getBody();
            $sRegExp = '~<link rel="image_src" href="([^"]+)">~';
            if (!preg_match($sRegExp, $sContent, $aMatches)) {
                continue;
            }
            $sFilename = $aMatches[1];
            $sPath = $this->getFolder() . DIRECTORY_SEPARATOR .
                str_pad($index++, 4, '0', STR_PAD_LEFT) . '-' . basename($sFilename);
            $aReturn[$sPath] = $sFilename;
        }
        return $aReturn;
    }

    private function getFolder(): string
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
