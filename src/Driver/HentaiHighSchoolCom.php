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
                ')/((?<year>[0-9]{4})/)?((?<collection>[^/]+)/)?(?<album>[^/]+)/' .
                '(?<type>image|index)(?<number>[0-9]{3,4})\.(?<extension>html|php)$~',
                $this->sUrl,
                $this->aMatches
            );
        }

        /**
         * @return array|string[]
         */
        public function getDownloadables()
        {
            $aParts = [$this->getDomain()];
            if (isset($this->aMatches['year']) && $this->aMatches['year']) {
                $aParts[] = $this->aMatches['year'];
            }
            if (isset($this->aMatches['collection']) && $this->aMatches['collection']) {
                $aParts[] = $this->aMatches['collection'];
            }
            $aParts[] = $this->aMatches['album'];
            $sUrl = 'http://www.' . implode('/', $aParts);
            $sExtension = $this->aMatches['extension'];
            $sType = $this->aMatches['type'];
            $aReturn = [];
            for ($i = 1; $i <= 9999; $i++) {
                $sIndex = str_pad($i, strlen($this->aMatches['number']), '0', STR_PAD_LEFT);
                $sImgUrl = "${sUrl}/${sType}${sIndex}.${sExtension}";
                try {
                    /* @var \PHPHtmlParser\Dom\AbstractNode $oImg */
                    $oImg = $this->getDomParser()->load((string)$this->getClient()->get($sImgUrl)->getBody())
                        ->find('table td a img')[0];
                    if (is_null($oImg)) {
                        return $aReturn;
                    }
                    $sSrc = $oImg->getAttribute('src');
                    $sFilename = strpos('//', $sSrc) === false
                        ? $sUrl . ($sSrc{0} == '/' ?: '/') . $sSrc
                        : $sSrc;
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
