<?php

namespace Yamete\Driver;

if (!class_exists(FreeFamousCartoonPornCom::class)) {
    class FreeFamousCartoonPornCom extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = 'freefamouscartoonporn.com';

        protected function getDomain()
        {
            return self::DOMAIN;
        }

        public function canHandle()
        {
            return (bool)preg_match(
                '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/content/(?<album>[^/]+)/index\.html$~',
                $this->sUrl,
                $this->aMatches
            );
        }

        public function getDownloadables()
        {
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $aReturn = [];
            $i = 0;
            $oIterator = $this->getDomParser()->load((string)$oRes->getBody(), ['cleanupInput' => false])
                ->find('#aniimated-thumbnials a');
            foreach ($oIterator as $oLink) {
                /* @var \DOMElement $oLink */
                $sFilename = $oLink->getAttribute('href');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($i++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            return $aReturn;
        }

        private function getFolder()
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }
    }
}
