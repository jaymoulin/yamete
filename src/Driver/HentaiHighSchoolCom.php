<?php

namespace Yamete\Driver;

if (!class_exists(HentaiHighSchoolCom::class)) {
    class HentaiHighSchoolCom extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = 'hentai-high-school.com';

        protected function getDomain()
        {
            return self::DOMAIN;
        }

        public function canHandle()
        {
            return (bool)preg_match(
                '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/(?<year>[0-9]{4})/(?<album>[^/]+)/image([0-9]{4})\.html$~',
                $this->sUrl,
                $this->aMatches
            );
        }

        public function getDownloadables()
        {
            $sUrl = 'http://www.' . $this->getDomain() . '/' . $this->aMatches['year'] . '/' . $this->aMatches['album'];
            $aReturn = [];
            for ($i = 1; $i <= 9999; $i++) {
                $sIndex = str_pad($i, 4, '0', STR_PAD_LEFT);
                $sImgUrl = "$sUrl/image$sIndex.html";
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
            return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
        }
    }
}
