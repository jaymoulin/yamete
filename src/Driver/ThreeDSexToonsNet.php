<?php

namespace Yamete\Driver;

use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Yamete\DriverAbstract;

if (!class_exists(ThreeDSexToonsNet::class)) {
    class ThreeDSexToonsNet extends DriverAbstract
    {
        private array $aMatches = [];
        private const DOMAIN = '3dsextoons.net';

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
            $sUrl = $this->sUrl;
            $oRes = $this->getClient()->request('GET', $sUrl);
            $aReturn = [];
            $iNbImg = count($this->getDomParser()->loadStr((string)$oRes->getBody())->find('.yukkal a.tumb'));
            for ($index = 1; $index <= $iNbImg; $index++) {
                $sFilename = $sUrl . str_pad($index, 2, '0', STR_PAD_LEFT) . '.jpg';
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
