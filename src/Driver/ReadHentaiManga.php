<?php

namespace Yamete\Driver;

class ReadHentaiManga extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'readhentaimanga.com';

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://(' . strtr(self::DOMAIN, ['.' => '\.']) . ')/(?<album>[^/]+)~',
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
        $this->sUrl = 'http://' . self::DOMAIN . '/' . $this->aMatches['album'] . '/';
        $oRes = $this->getClient()->request('GET', $this->sUrl . '1/1/');
        $aReturn = [];
        $i = 0;
        $nbChapters = count($this->getDomParser()->load((string)$oRes->getBody())->find('.nav_chp option'));
        for ($chapter = 1; $chapter <= $nbChapters; $chapter++) {
            $this->aMatches['chapter'] = $chapter;
            $oRes = $this->getClient()->request('GET', $this->sUrl . $chapter . '/1/');
            $nbPages = count(
                $this->getDomParser()->load((string)$oRes->getBody())->find('.nav_pag option')
            ) / 2;
            for ($page = 1; $page <= $nbPages; $page++) {
                $oRes = $this->getClient()->request('GET', $this->sUrl . $chapter . '/' . $page . '/');
                /** @var \DOMElement $oImg */
                $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#main_img')[0];
                $sFilename = $this->decodeUrl($oImg->getAttribute('src'));
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function decodeUrl($sUrl)
    {
        $aMap = explode('&#38;#x', $sUrl);
        unset($aMap[0]);
        $sReturn = '';
        foreach ($aMap as $sChar) {
            $sReturn .= chr(hexdec($sChar));
        }
        return $sReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album'], $this->aMatches['chapter']]);
    }
}
