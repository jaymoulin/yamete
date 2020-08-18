<?php

namespace Yamete\Driver;

use \ArrayIterator;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

if (!class_exists(FreeFamousCartoonPornCom::class)) {
    class FreeFamousCartoonPornCom extends DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = 'freefamouscartoonporn.com';

        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^https?://(' . strtr($this->getDomain(), ['.' => '\.']) . ')/content/(?<album>[^/]+)/index\.html$~',
                $this->sUrl,
                $this->aMatches
            );
        }

        protected function getSelectors(): array
        {
            return [
                '#grid-content a',
                '#aniimated-thumbnials a',
            ];
        }

        /**
         * @return array|string[]
         * @throws GuzzleException
         */
        public function getDownloadables(): array
        {
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $aReturn = [];
            $index = 0;
            $oIterator = new ArrayIterator;
            foreach ($this->getSelectors() as $sSelector) {
                $oIterator = $this->getDomParser()->load((string)$oRes->getBody(), ['cleanupInput' => false])
                    ->find($sSelector);
                if (count($oIterator) !== 0) {
                    break;
                }
            }
            foreach ($oIterator as $oLink) {
                /**
                 * @var AbstractNode $oLink
                 * @var AbstractNode $oImage
                 */
                $sUrl = 'http://' . $this->getDomain() . $oLink->getAttribute('href');
                $sSelector = '.container-gal-item img';
                $oImage = $this->getDomParser()->load(
                    (string)$this->getClient()->request('GET', $sUrl)->getBody(),
                    ['cleanupInput' => false]
                )
                    ->find($sSelector)[0];
                if (!$oImage) {
                    continue;
                }
                $sFilename = $oImage->getAttribute('src');
                $sFilename = strpos($sFilename, 'http') !== false
                    ? $sFilename
                    : 'http://' . $this->getDomain() . $sFilename;
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
