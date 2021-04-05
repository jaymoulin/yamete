<?php

namespace Yamete\Driver;

use ArrayIterator;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use PHPHtmlParser\Options;
use Yamete\DriverAbstract;

if (!class_exists(FreeFamousCartoonPornCom::class)) {
    class FreeFamousCartoonPornCom extends DriverAbstract
    {
        private array $aMatches = [];
        private const DOMAIN = 'freefamouscartoonporn.com';

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

        /**
         * Selectors for the url list
         * @return string[]
         */
        protected function getSelectors(): array
        {
            return [
                '.grid-item-content a',
                '#grid-content a',
                '#aniimated-thumbnials a',
            ];
        }

        /**
         * @return array
         * @throws GuzzleException
         * @throws ChildNotFoundException
         * @throws CircularException
         * @throws ContentLengthException
         * @throws LogicalException
         * @throws NotLoadedException
         * @throws StrictException
         */
        public function getDownloadables(): array
        {
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $aReturn = [];
            $index = 0;
            $oIterator = new ArrayIterator;
            foreach ($this->getSelectors() as $sSelector) {
                $oIterator = $this->getDomParser()
                    ->loadStr((string)$oRes->getBody(), (new Options)->setCleanupInput(false))
                    ->find($sSelector);
                if (count($oIterator) !== 0) {
                    break;
                }
            }
            foreach ($oIterator as $oLink) {
                $sUrl = 'http://' . $this->getDomain() . $oLink->getAttribute('href');
                $sSelector = '.container-gal-item img';
                $oImage = $this->getDomParser()->loadStr(
                    (string)$this->getClient()->request('GET', $sUrl)->getBody(),
                    (new Options)->setCleanupInput(false)
                )
                    ->find($sSelector)[0];
                if (!$oImage) {
                    continue;
                }
                $sFilename = $oImage->getAttribute('src');
                $sFilename = str_starts_with($sFilename, 'http')
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
