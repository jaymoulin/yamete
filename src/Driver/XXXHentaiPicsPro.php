<?php

namespace Yamete\Driver;

use PHPHtmlParser\Dom\AbstractNode;
use Psr\Http\Message\ResponseInterface;
use Yamete\DriverAbstract;

if (!class_exists(XXXHentaiPicsPro::class)) {
    class XXXHentaiPicsPro extends DriverAbstract
    {
        private const DOMAIN = 'xxxhentaipics.pro';
        private $aMatches = [];
        private $aReturn = [];
        private $iPointer = 0;

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
         * @return array|string[]
         * @throws GuzzleException
         */
        public function getDownloadables(): array
        {
            $this->sUrl = strpos($this->sUrl, '?') ? substr($this->sUrl, 0, strpos($this->sUrl, '?')) : $this->sUrl;
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $this->sUrl .= ($this->sUrl{strlen($this->sUrl) - 1} != '/') ? '/' : '';
            $this->aReturn = [];
            $this->iPointer = 0;
            $sSelectorOptions = '.container .part-select option';
            $oOptionsIterator = $this->getDomParser()->load((string)$oRes->getBody())->find($sSelectorOptions);
            foreach ($oOptionsIterator as $oOptionChap) {
                /* @var AbstractNode $oOptionChap */
                $sLink = 'http://www.' . $this->getDomain() . $oOptionChap->getAttribute('value');
                $oRes = $this->getClient()->request('GET', $sLink);
                $this->findForRes($oRes);
            }
            if (!$this->iPointer) {
                $this->findForRes($oRes);
            }
            return $this->aReturn;
        }

        private function findForRes(ResponseInterface $oRes): void
        {
            foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($this->getSelector()) as $oLink) {
                /* @var AbstractNode $oLink */
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
