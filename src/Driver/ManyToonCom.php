<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Traversable;
use Yamete\DriverAbstract;

if (!class_exists(ManyToonCom::class)) {
    class ManyToonCom extends DriverAbstract
    {
        protected $aMatches = [];
        private const DOMAIN = 'manytoon.com';

        /**
         * @return string
         */
        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        public function canHandle(): bool
        {
            $sReg = '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/(?<category>[^/]+)/(?<album>[^/]+)~';
            return (bool)preg_match($sReg, $this->sUrl, $this->aMatches);
        }

        /**
         * @return array|string[]
         * @throws GuzzleException
         */
        public function getDownloadables(): array
        {
            /**
             * @var Traversable $oChapters
             * @var AbstractNode $oChapter
             * @var AbstractNode $oImg
             */
            $sUrl = 'https://' . $this->getDomain() . '/' . $this->aMatches['category']
                . '/' . $this->aMatches['album'] . '/';
            $oRes = $this->getClient()->request('GET', $sUrl);
            $oChapters = $this->getDomParser()->loadStr((string)$oRes->getBody())->find('li.wp-manga-chapter a');
            $aChapters = iterator_to_array($oChapters);
            krsort($aChapters);
            $index = 0;
            $aReturn = [];
            foreach ($aChapters as $oChapter) {
                $oRes = $this->getClient()->request('GET', $oChapter->getAttribute('href'));
                foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.reading-content img') as $oImg) {
                    $sFilename = trim($oImg->getAttribute('src'));
                    $iPos = strpos($sFilename, '?');
                    $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                        . '-' . basename(substr($sFilename, 0, $iPos));
                    $aReturn[$sBasename] = $sFilename;
                }
            }
            return $aReturn;
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }
    }
}
