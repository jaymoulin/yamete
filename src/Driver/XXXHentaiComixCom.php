<?php

namespace Yamete\Driver;

class XXXHentaiComixCom extends \Yamete\DriverAbstract
{
    const DOMAIN = 'xxxhentaicomix.com';
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
     * @throws \GuzzleHttp\Exception\GuzzleException
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
            /* @var \PHPHtmlParser\Dom\AbstractNode $oOptionChap */
            $sLink = 'http://www.' . $this->getDomain() . $oOptionChap->getAttribute('value');
            $oRes = $this->getClient()->request('GET', $sLink);
            $this->findForRes($oRes);
        }
        if (!$this->iPointer) {
            $this->findForRes($oRes);
        }
        return $this->aReturn;
    }

    private function findForRes(\Psr\Http\Message\ResponseInterface $oRes): void
    {
        foreach ($this->getDomParser()->load((string)$oRes->getBody())->find($this->getSelector()) as $oLink) {
            /* @var \PHPHtmlParser\Dom\AbstractNode $oLink */
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
