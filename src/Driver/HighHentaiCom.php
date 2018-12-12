<?php

namespace Yamete\Driver;


if (!class_exists(HighHentaiCom::class)) {
    class HighHentaiCom extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = 'high-hentai.com';

        protected function getDomain()
        {
            return self::DOMAIN;
        }

        public function canHandle()
        {
            return (bool)preg_match(
                '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/hentai(?<year>[0-9]{4})/(?<album>[^/]+)/~',
                $this->sUrl,
                $this->aMatches
            );
        }

        protected function getStart()
        {
            return 0;
        }

        public function getDownloadables()
        {
            $sUrl = 'http://www.' . $this->getDomain() . '/hentai' . $this->aMatches['year'] . '/' . $this->aMatches['album'];
            $aReturn = [];
            for ($i = $this->getStart(); $i <= 999; $i++) {
                $sIndex = str_pad($i, 3, '0', STR_PAD_LEFT);
                $sImgUrl = "$sUrl/image$sIndex.php";
                try {
                    /* @var \PHPHtmlParser\Dom\AbstractNode $oImg */

                    $oImg = $this->getDomParser()->load((string)$this->getClient()->get($sImgUrl)->getBody())
                        ->find('a img')[0];
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
}
