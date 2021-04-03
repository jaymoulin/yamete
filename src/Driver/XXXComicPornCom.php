<?php

namespace Yamete\Driver;

use AppendIterator;
use ArrayIterator;
use GuzzleHttp\Exception\GuzzleException;
use PHPHtmlParser\Dom\AbstractNode;
use Yamete\DriverAbstract;

if (!class_exists(XXXComicPornCom::class)) {
    class XXXComicPornCom extends DriverAbstract
    {
        private $aMatches = [];
        private const DOMAIN = 'xxxcomicporn.com';

        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/(?<lang>[a-z]{2}/)?(galleries|images|pictures|gallery|videos)/(?<album>[^/?]+)[/?]?~',
                $this->sUrl,
                $this->aMatches
            );
        }

        protected function getSelector(): string
        {
            return '.portfolio-normal-width figure a';
        }

        /**
         * @return array|string[]
         * @throws GuzzleException
         */
        public function getDownloadables(): array
        {
            $this->sUrl = strpos($this->sUrl, '?') ? substr($this->sUrl, 0, strpos($this->sUrl, '?')) : $this->sUrl;
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $this->sUrl .= ($this->sUrl[strlen($this->sUrl) - 1] != '/') ? '/' : '';
            $aReturn = new AppendIterator();
            $sBody = (string)$oRes->getBody();
            $index = 0;
            $oOptions = $this->getDomParser()->loadStr($sBody)->find('.part-select option');
            if (!count($oOptions)) {
                list($oCursor) = $this->getForBody($sBody, $index);
                $aReturn->append($oCursor);
                return iterator_to_array($aReturn);
            }
            $aParsed = [];
            foreach ($oOptions as $oOption) {
                /**
                 * @var AbstractNode $oOption
                 */
                $sUrl = 'http://' . $this->getDomain() . $oOption->getAttribute('value');
                if (isset($aParsed[$sUrl])) {
                    continue;
                }
                $aParsed[$sUrl] = true;
                $sCurrentBody = (string)$this->getClient()->request('GET', $sUrl)->getBody();
                list($oCursor, $index) = $this->getForBody($sCurrentBody, $index);
                $aReturn->append($oCursor);
            }
            return iterator_to_array($aReturn);
        }

        /**
         * @param string $sBody
         * @param int $iIndex
         * @return array
         */
        private function getForBody(string $sBody, int $iIndex): array
        {
            $aReturn = [];
            foreach ($this->getDomParser()->loadStr($sBody)->find($this->getSelector()) as $oLink) {
                /**
                 * @var AbstractNode $oLink
                 */
                $sFilename = $oLink->getAttribute('data-img') . $oLink->getAttribute('data-ext');
                $sFilename = strpos($sFilename, 'http') !== false
                    ? $sFilename
                    : 'http://' . $this->getDomain() . $sFilename;
                $oRes = $this->getClient()->request('GET', $sFilename, ["http_errors" => false]);
                if ($oRes->getStatusCode() !== 200) {
                    continue;
                }
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($iIndex++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            return [new ArrayIterator($aReturn), $iIndex];
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }
    }
}
