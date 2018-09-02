<?php

namespace Yamete\Driver;

class Erofus extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    private $aReturn = [];
    const DOMAIN = 'erofus.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www.(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/comics/(?<collection>[^/]+)/(?<album>[^/]+)~',
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
        $this->aReturn = [];
        $this->getLinks($this->sUrl);
        return $this->aReturn;
    }

    /**
     * @param string $sUrl
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getLinks($sUrl)
    {
        $oRes = $this->getClient()->request('GET', $sUrl);
        $bFound = false;
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('.row-content a') as $oLink) {
            /**
             * @var \PHPHtmlParser\Dom\AbstractNode $oLink
             */
            $sHref = $oLink->getAttribute('href');
            if (!$oLink->getAttribute('title')) {
                continue;
            }
            $this->getLinks(strpos($sHref, '://') !== false ? $sHref : 'https://www.' . self::DOMAIN . $sHref);
            $bFound = true;
        }
        if ($bFound) {
            return;
        }
        $oRes = $this->getClient()->request('GET', $sUrl);
        /**
         * @var \PHPHtmlParser\Dom\AbstractNode $oImg
         */
        $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#picture-full img')[0];
        $sFilename = 'https://www.' . self::DOMAIN . $oImg->getAttribute('src');
        $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad(count($this->aReturn) + 1, 5, '0', STR_PAD_LEFT)
            . '-' . basename($sFilename);
        $this->aReturn[$sBasename] = $sFilename;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
