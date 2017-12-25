<?php

namespace Yamete\Driver;


class MangEroticaCom extends \Yamete\DriverAbstract
{
    private $aMatches = [];
    const DOMAIN = 'mangerotica.com';

    protected function getDomain()
    {
        return self::DOMAIN;
    }

    public function canHandle()
    {
        return (bool)preg_match(
            '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
            ')/manga\-comics/(?<album>[^/]+)/~',
            $this->sUrl,
            $this->aMatches
        );
    }

    public function getDownloadables()
    {
        $sUrl = 'http://www.' . $this->getDomain() . '/manga-comics/' . $this->aMatches['album'];
        $aReturn = [];
        for ($i = 1; $i <= 9999; $i++) {
            $sIndex = str_pad($i, 4, '0', STR_PAD_LEFT);
            $sImgUrl = "$sUrl/image$sIndex.php";
            try {
                /* @var \DOMElement $oImg */

                $oImg = $this->getDomParser()->load((string)$this->getClient()->get($sImgUrl)->getBody())
                    ->find('img.img_full_size_image')[0];
                if (is_null($oImg)) {
                    return $aReturn;
                }
                $sFilename = $sUrl . $oImg->getAttribute('src');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . $sIndex . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            } catch (\Exception $e) {
                return $aReturn;
            }
        }
        return $aReturn;
    }

    private function getFolder()
    {
        return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
    }
}
