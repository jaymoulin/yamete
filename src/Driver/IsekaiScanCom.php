<?php

namespace Yamete\Driver;

use PHPHtmlParser\Dom\AbstractNode;


if (!class_exists(IsekaiScanCom::class)) {
    class IsekaiScanCom extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = 'isekaiscan.com';

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^https?://(' . strtr($this->getdomain(), ['.' => '\.']) . ')/manga/(?<album>[^/]+)~',
                $this->sUrl,
                $this->aMatches
            );
        }

        /**
         * @return string
         */
        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        /**
         * Where to download
         * @return string
         */
        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }

        /**
         * @return array|string[]
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function getDownloadables(): array
        {
            /**
             * @var \iterator $oChapters
             * @var AbstractNode[] $aChapters
             * @var AbstractNode[] $oPages
             */
            $sUrl = 'https://' . $this->getDomain() . '/manga/' . $this->aMatches['album'] . '/';
            $oResult = $this->getClient()->request('GET', $sUrl);
            $oChapters = $this->getDomParser()->load((string)$oResult->getBody())->find('.wp-manga-chapter a');
            $aChapters = iterator_to_array($oChapters);
            krsort($aChapters);
            $aReturn = [];
            $index = 0;
            foreach ($aChapters as $oChapter) {
                $oResult = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
                $sRegexp = '~data-src="([^"]+)" class="wp-manga~';
                $aMatches = [];
                if (!preg_match_all($sRegexp, (string)$oResult->getBody(), $aMatches)) {
                    continue;
                }
                foreach ($aMatches[1] as $sFilename) {
                    $sFilename = trim($sFilename);
                    $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                        . '-' . basename($sFilename);
                    $aReturn[$sBasename] = $sFilename;
                }
            }
            return $aReturn;
        }
    }
}
