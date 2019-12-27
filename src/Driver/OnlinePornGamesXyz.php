<?php

namespace Yamete\Driver;

if (!class_exists(OnlinePornGamesXyz::class)) {
    class OnlinePornGamesXyz extends \Yamete\DriverAbstract
    {
        const DOMAIN = 'onlineporngames.xyz';
        private $aMatches = [];

        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^(?<scheme>https?)://(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/(?<album>[^/?]+)[/?]?~',
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
            $iParamsPos = strpos($this->sUrl, '?');
            $this->sUrl = $iParamsPos ? substr($this->sUrl, 0, $iParamsPos) : $this->sUrl;
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $aReturn = [];
            $index = 0;
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('img.img-responsive') as $oImg) {
                /* @var \PHPHtmlParser\Dom\AbstractNode $oImg */
                if (!$oImg->getAttribute('data-original')) {
                    continue;
                }
                $sFilename = $oImg->getAttribute('data-original');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            return $aReturn;
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }
    }
}
