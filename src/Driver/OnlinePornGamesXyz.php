<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use Yamete\DriverAbstract;

if (!class_exists(OnlinePornGamesXyz::class)) {
    class OnlinePornGamesXyz extends DriverAbstract
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
         * @throws GuzzleException
         */
        public function getDownloadables(): array
        {
            $iParamsPos = strpos($this->sUrl, '?');
            $this->sUrl = $iParamsPos ? substr($this->sUrl, 0, $iParamsPos) : $this->sUrl;
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $aReturn = [];
            $index = 0;
            $aMatches = [];
            $aMatchesCover = [];
            $sBody = (string)$oRes->getBody();
            if (
                !preg_match_all('~data-original="([^"]+)"~', $sBody, $aMatches) or
                !preg_match_all('~<img class="img-responsive" src="([^"]+)"~', $sBody, $aMatchesCover)
            ) {
                return [];
            }
            foreach ($aMatchesCover[1] as $sFilename) {
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            foreach (array_slice($aMatches[1], 3) as $iKey => $sFilename) {
                if ($iKey % 2 === 0) {
                    continue;
                }
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
