<?php

namespace Yamete\Driver;

class HentaiComics extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'hentai-comics.org';

    public function canHandle()
    {
        return preg_match(
            '~^https?://' . strtr(self::DOMAIN, ['.' => '\.', '-' => '\-']) .
            '/gallery/(?<id>[^/]+)/(?<album>.+)\.html$~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $sFirstPage = "https://" . self::DOMAIN . "/view/{$this->aMatches['id']}/1/{$this->aMatches['album']}.html";
        $oRes = $this->getClient()->request('GET', $sFirstPage);
        $aReturn = [];
        if (preg_match('~var d=(?<json>[^;]+);~', (string)$oRes->getBody(), $aMatches)) {
            foreach (\GuzzleHttp\json_decode($aMatches['json'], true) as $aOption) {
                $sFilename = $aOption['chapter_image'];
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
    }
}
