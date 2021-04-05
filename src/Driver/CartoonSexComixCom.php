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

if (!class_exists(CartoonSexComixCom::class)) {
    class CartoonSexComixCom extends DriverAbstract
    {
        private const DOMAIN = 'cartoonsexcomix.com';
        private array $aMatches = [];

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^(?<scheme>https?)://(www\.)?(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/(pictures|gallery|galleries|videos)/(?<album>[^/?]+)[/?]?~',
                $this->sUrl,
                $this->aMatches
            );
        }

        protected function getDomain(): string
        {
            return self::DOMAIN;
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
            $iParamsPos = strpos($this->sUrl, '?');
            $this->sUrl = $iParamsPos ? substr($this->sUrl, 0, $iParamsPos) : $this->sUrl;
            $oRes = $this->getClient()->request('GET', $this->sUrl, ['http_errors' => false]);
            $this->sUrl .= ($this->sUrl[strlen($this->sUrl) - 1] != '/') ? '/' : '';
            $aReturn = [];
            $index = 0;
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find($this->getSelector()) as $oLink) {
                $sFilename = $oLink->getAttribute('href');
                $sFilename = str_contains($sFilename, 'http')
                    ? $sFilename
                    : (
                    str_contains($sFilename, '//')
                        ? $this->aMatches['scheme'] . ':' . $sFilename
                        : $this->aMatches['scheme'] . '://www.' . $this->getDomain() . $sFilename);
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($index++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            return $aReturn;
        }

        protected function getSelector(): string
        {
            return '.my-gallery figure a';
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }
    }
}
