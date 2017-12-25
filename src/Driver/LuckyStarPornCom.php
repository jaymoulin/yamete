<?php

namespace Yamete\Driver;


if (!class_exists(LuckyStarPornCom::class)) {
    class LuckyStarPornCom extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = 'luckystarporn.com';

        protected function getDomain()
        {
            return self::DOMAIN;
        }

        public function canHandle()
        {
            return (bool)preg_match(
                '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/hentai(?<year>[0-9]{4})/(?<album>[^/]+)/(image|index)([0-9]{3})\.php$~',
                $this->sUrl,
                $this->aMatches
            );
        }

        public function getDownloadables()
        {
            $sUrl = 'http://www.' . $this->getDomain() . '/hentai' . $this->aMatches['year'] . '/' . $this->aMatches['album'];
            $aReturn = [];
            for ($i = 0; $i <= 999; $i++) {
                $sIndex = str_pad($i, 3, '0', STR_PAD_LEFT);
                $sImgUrl = "$sUrl/image$sIndex.php";
                try {
                    /* @var \DOMElement $oImg */

                    $oImg = $this->getDomParser()->load((string)$this->getClient()->get($sImgUrl)->getBody())
                        ->find('table td a img')[0];
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
