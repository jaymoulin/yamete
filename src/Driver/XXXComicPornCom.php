<?php

namespace Yamete\Driver;

if (!class_exists(XXXComicPornCom::class)) {
    class XXXComicPornCom extends \Yamete\DriverAbstract
    {
        private $aMatches = [];
        const DOMAIN = 'xxxcomicporn.com';

        protected function getDomain(): string
        {
            return self::DOMAIN;
        }

        public function canHandle(): bool
        {
            return (bool)preg_match(
                '~^https?://www\.(' . strtr($this->getDomain(), ['.' => '\.', '-' => '\-',]) .
                ')/(?<lang>[a-z]{2}/)?(galleries|images|pictures|gallery)/(?<album>[^/?]+)[/?]?~',
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
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function getDownloadables(): array
        {
            $this->sUrl = strpos($this->sUrl, '?') ? substr($this->sUrl, 0, strpos($this->sUrl, '?')) : $this->sUrl;
            $oRes = $this->getClient()->request('GET', $this->sUrl);
            $this->sUrl .= ($this->sUrl{strlen($this->sUrl) - 1} != '/') ? '/' : '';
            $aReturn = new \AppendIterator();
            $sBody = (string)$oRes->getBody();
            $i = 0;
            $oOptions = $this->getDomParser()->load($sBody)->find('.part-select option');
            if (count($oOptions)) {
                foreach ($oOptions as $oOption) {
                    /**
                     * @var \PHPHtmlParser\Dom\AbstractNode $oOption
                     */
                    $sUrl = 'http://' . $this->getDomain() . $oOption->getAttribute('value');
                    $sCurrentBody = (string)$this->getClient()->request('GET', $sUrl)->getBody();
                    list($oCursor, $i) = $this->getForBody($sCurrentBody, $i);
                    $aReturn->append($oCursor);
                }
            } else {
                list($oCursor) = $this->getForBody($sBody, $i);
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
            foreach ($this->getDomParser()->load($sBody)->find($this->getSelector()) as $oLink) {
                /**
                 * @var \PHPHtmlParser\Dom\AbstractNode $oLink
                 */
                $sFilename = $oLink->getAttribute('data-img') . $oLink->getAttribute('data-ext');
                $sBasename = $this->getFolder() . DIRECTORY_SEPARATOR . str_pad($iIndex++, 5, '0', STR_PAD_LEFT)
                    . '-' . basename($sFilename);
                $aReturn[$sBasename] = $sFilename;
            }
            return [new \ArrayIterator($aReturn), $iIndex];
        }

        private function getFolder(): string
        {
            return implode(DIRECTORY_SEPARATOR, [$this->getDomain(), $this->aMatches['album']]);
        }
    }
}
