<?php

namespace Yamete\Driver;

if (!class_exists(ThreeDPornPics::class)) {
    class ThreeDPornPics extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = '3dpornpics.pro';

        protected function getDomain()
        {
            return self::DOMAIN;
        }

        public function canHandle()
        {
            return (bool)preg_match(
                '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/(?<lang>[a-z]{2}/)?(galleries|images|pictures|gallery)/(?<album>[^/?]+)[/?]?~',
                $this->sUrl,
                $this->aMatches
            );
        }

        protected function getSelector()
        {
            return '.portfolio-normal-width figure a';
        }

        /**
         * @return array|string[]
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function getDownloadables()
        {
            $this->sUrl = strpos($this->sUrl, '?') ? substr($this->sUrl, 0, strpos($this->sUrl, '?')) : $this->sUrl;
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $this->sUrl .= ($this->sUrl{strlen($this->sUrl) - 1} != '/') ? '/' : '';
            $aReturn = [];
            $i = 0;
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($this->getSelector()) as $oLink) {
                /**
                 * @var \DOMElement $oLink
                 */
                $sFilename = $oLink->getAttribute('data-img') . $oLink->getAttribute('data-ext');
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
