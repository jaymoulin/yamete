<?php

namespace Yamete\Driver;

if (!class_exists(HentaiRulesNet::class)) {
    class HentaiRulesNet extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = 'hentairules.net';

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^https?://(www\.)?(' . strtr($this->getDomain(), ['.' => '\.']) .
                ')/galleries(?<gallery>[0-9]+)/index\.php\?/category/(?<album>[0-9]+)$~',
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
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $aReturn = [];
            $index = 0;
            $iNbPage = count($this->getDomParser()->load((string)$oRes->getBody())->find('.navigationBar > a')) + 1;
            if (!$iNbPage) {
                $iNbPage = 1;
            }
            $sBaseUrl = 'http://www.' . $this->getDomain() . '/galleries' . $this->aMatches['gallery'];
            for ($page = 0; $page < $iNbPage; $page++) {
                $sUrl = $sBaseUrl . '/index.php?/category/' . $this->aMatches['album'] . '/start-' . $page . '00';
                $oRes = $this->getClient()->request('GET', $sUrl);
                foreach ($this->getDomParser()->load((string)$oRes->getBody())->find('#thumbnails li a') as $oLink) {
                    /**
                     * @var \PHPHtmlParser\Dom\AbstractNode $oLink
                     * @var \PHPHtmlParser\Dom\AbstractNode $oImg
                     */
                    $sUrl = $sBaseUrl . '/' . $oLink->getAttribute('href');
                    $oRes = $this->getClient()->request('GET', $sUrl);
                    $oImg = $this->getDomParser()->load((string)$oRes->getBody())->find('#theImage img')[0];
                    $sFilename = $sBaseUrl . '/' . $oImg->getAttribute('data-cfsrc');
                    $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                        . '-' . basename($sFilename);
                    $aReturn[$sBasename] = $sFilename;
                }
            }
            return $aReturn;
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [self::DOMAIN, $this->aMatches['album']]);
        }

        protected function getDomain(): string
        {
            return self::DOMAIN;
        }
    }
}
