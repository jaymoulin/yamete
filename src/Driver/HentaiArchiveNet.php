<?php

namespace Yamete\Driver;

class HentaiArchiveNet extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai-archive.net';

    protected function getDomain(): string
    {
        return self::DOMAIN;
    }

    public function canHandle(): bool
    {
        return (bool)preg_match(
            '~^https?://www.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-']) .
            ')/(?<album>[^/]+)/$~',
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
        $aReturn = [];
        $i = 0;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('figure.dgwt-jg-item a img') as $oImg) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oImg
             */
            $aUrls = explode(',', $oImg->getAttribute('data-jg-srcset'));
            $aTmp = [];
            foreach ($aUrls as $sItem) {
                list($sUrl, $sSize) = explode(' ', trim($sItem));
                $aTmp[$sSize] = $sUrl;
            }
            ksort($aTmp);
            $sFilename = array_pop($aTmp);
            $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
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
