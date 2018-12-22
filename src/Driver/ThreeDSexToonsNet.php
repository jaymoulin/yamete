<?php

namespace Yamete\Driver;

if (!class_exists(ThreeDSexToonsNet::class)) {
    class ThreeDSexToonsNet extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = '3dsextoons.net';

        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^https?://(www\.)?(' . strtr($this->getDomain(), ['.' => '\.']) .
                ')/gals/(?<site>[^/]+)/(?<album>[^/]+)/$~',
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
            $sUrl = str_replace($this->getDomain(), 'page-x.com', $this->sUrl);
            $oRes = $this->getClient()->request('GET', $sUrl);
            $aReturn = [];
            $iNbImg = count($this->getDomParser()->load((string)$oRes->getBody())->find('#gallery2 a'));
            for ($i = 1; $i <= $iNbImg; $i++) {
                $sFilename = $sUrl . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg';
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            return $aReturn;
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
        }
    }
}
