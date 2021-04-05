<?php

namespace Yamete\Driver;


use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\CircularException;
use PHPHtmlParser\Exceptions\ContentLengthException;
use PHPHtmlParser\Exceptions\LogicalException;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPHtmlParser\Exceptions\StrictException;
use Psr\Http\Message\ResponseInterface;
use Yamete\DriverAbstract;

if (!class_exists(XXXHentaiPicsPro::class)) {
    class XXXHentaiPicsPro extends DriverAbstract
    {
        private const DOMAIN = 'xxxhentaipics.pro';
        private array $aMatches = [];
        private array $aReturn = [];
        private int $iPointer = 0;

        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/(pictures|gallery|galleries)/(?<album>[^/?]+)[/?]?~',
                $this->sUrl,
                $this->aMatches
            );
        }

        protected function getSelector(): string
        {
            return '.gallery-thumbs figure a';
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
            $this->sUrl = strpos($this->sUrl, '?') ? substr($this->sUrl, 0, strpos($this->sUrl, '?')) : $this->sUrl;
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $this->sUrl .= ($this->sUrl[strlen($this->sUrl) - 1] != '/') ? '/' : '';
            $this->aReturn = [];
            $this->iPointer = 0;
            $sSelectorOptions = '.container .part-select option';
            $oOptionsIterator = $this->getDomParser()->loadStr((string)$oRes->getBody())->find($sSelectorOptions);
            foreach ($oOptionsIterator as $oOptionChap) {
                $sLink = 'http://www.' . $this->getDomain() . $oOptionChap->getAttribute('value');
                $oRes = $this->getClient()->request('GET', $sLink);
                $this->findForRes($oRes);
            }
            if (!$this->iPointer) {
                $this->findForRes($oRes);
            }
            return $this->aReturn;
        }

        /**
         * @param ResponseInterface $oRes
         * @throws ChildNotFoundException
         * @throws CircularException
         * @throws ContentLengthException
         * @throws LogicalException
         * @throws NotLoadedException
         * @throws StrictException
         */
        private function findForRes(ResponseInterface $oRes): void
        {
            foreach ($this->getDomParser()->loadStr((string)$oRes->getBody())->find($this->getSelector()) as $oLink) {
                $sFilename = $oLink->getAttribute('data-img') . '.' . trim($oLink->getAttribute('data-ext'), '.');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($this->iPointer++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $this->aReturn[$sBasename] = $sFilename;
            }
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }
    }
}
